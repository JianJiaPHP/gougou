<template>
  <div class="app-container">
    <el-row :gutter="10" class="btn-bt btnMb" type="flex">
      <el-col :span="6" class="searchBox">
        <el-input v-model="query.keyword" size="medium" placeholder="关键字" />
      </el-col>
      <el-col :span="18">
        <el-button
          type="primary"
          size="medium"
          icon="el-icon-search"
          @click="handleSearch"
        >搜索</el-button>
        <el-button v-if="btnPer.create" type="primary" size="medium" @click="createVisible = true">添加权限</el-button>
        <el-button v-if="btnPer.delete" type="danger" size="medium" @click="delAllSelection">批量删除</el-button>
      </el-col>
    </el-row>

    <el-table
      ref="multipleTable"
      v-loading="loading"
      :data="tableData.data"
      border
      class="table btnMb"
      header-cell-class-name="table-header"
      @selection-change="handleSelectionChange"
    >
      <el-table-column type="selection" width="55" align="center" />
      <el-table-column prop="id" label="ID" width="55" align="center" />
      <el-table-column prop="type" label="类型" align="center">
        <template slot-scope="scope">
          <el-tag v-if="scope.row.type === 0">菜单</el-tag>
          <el-tag v-if="scope.row.type === 1">按钮</el-tag>
          <el-tag v-if="scope.row.type === 2">接口</el-tag>
          <el-tag v-if="scope.row.type === 3">页面</el-tag>
        </template>

      </el-table-column>
<!--      <el-table-column prop="router" label="路由" align="center" />-->
      <el-table-column prop="name" label="名称" align="center" />
      <el-table-column prop="created_at" label="添加时间" align="center" />
      <el-table-column label="操作" align="center">
        <template slot-scope="scope">
          <el-button v-if="btnPer.update" type="text" icon="el-icon-edit" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
          <el-button
            v-if="btnPer.delete"
            type="text"
            icon="el-icon-delete"
            class="red"
            @click="handleDelete(scope.$index, scope.row)"
          >删除</el-button>
        </template>
      </el-table-column>
    </el-table>

    <div class="pagination">
      <el-pagination
        background
        :total="tableData.total"
        :page-sizes="[15, 30, 50,100]"
        :page_size="limit"
        :current-page="page"
        layout="total, sizes, prev, pager, next, jumper"
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
      />
    </div>

    <!--添加权限-->
    <el-dialog title="添加权限" :visible.sync="createVisible">
      <el-form ref="createForm" :model="createData" :rules="rules" label-width="auto">
        <el-form-item label="父级" prop="pid">
          <el-select v-model="createData.pid" placeholder="请选择" @focus="getPermissionsFather">
            <el-option
              v-for="(v,k) in permissionsFather"
              :key="k"
              :label="v.name"
              :value="v.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="类型" prop="type">
          <el-radio v-model="createData.type" label="0">菜单</el-radio>
          <el-radio v-model="createData.type" label="1">按钮</el-radio>
          <el-radio v-model="createData.type" label="2">接口</el-radio>
          <el-radio v-model="createData.type" label="3">页面</el-radio>
        </el-form-item>
        <el-form-item label="名称" prop="name">
          <el-input v-model="createData.name" placeholder="请输入名称" />
        </el-form-item>

        <el-form-item v-if="createData.type === '0' " label="图标" prop="icon">
          <el-input v-model="createData.icon" placeholder="请输入图标" />
        </el-form-item>

        <el-form-item v-if="createData.type === '1' " label="按钮" prop="btn_key">
          <el-input v-model="createData.btn_key" placeholder="请输入按钮权限" />
        </el-form-item>

        <template v-if="createData.type === '0' || createData.type === '3' ">
          <el-form-item label="组件" prop="component">
            <el-input v-model="createData.component" placeholder="请输入组件" />
          </el-form-item>
          <el-form-item label="路由" prop="router">
            <el-input v-model="createData.router" placeholder="请输入路由" />
          </el-form-item>
        </template>

        <el-form-item v-if="createData.type === '2'" label="路由" prop="router">
          <el-select v-model="createData.router" placeholder="请选择" @focus="getPermissionsIntersection">
            <el-option
              v-for="(v,k) in permissionsIntersection"
              :key="k"
              :label="v.router+'【'+v.method+'】'"
              :value="v.router+','+v.method"
            />
          </el-select>
        </el-form-item>

        <el-form-item label="排序" prop="sort">
          <el-slider v-model="createData.sort" />
        </el-form-item>
      </el-form>

      <div slot="footer" class="dialog-footer">
        <el-button @click="createVisible = false">取 消</el-button>
        <el-button type="primary" :loading="load" @click="createSubmit('createForm')">确 定</el-button>
      </div>
    </el-dialog>

    <!--修改权限-->
    <el-dialog title="修改权限" :visible.sync="updateVisible">
      <el-form ref="updateForm" :model="updateData" :rules="rules" label-width="auto">
        <el-form-item label="父级" prop="pid">
          <el-select v-model="updateData.pid" placeholder="请选择">
            <el-option
              v-for="(v,k) in permissionsFather"
              :key="k"
              :label="v.name"
              :value="v.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="类型" prop="type">
          <el-radio v-model="updateData.type" label="0">菜单</el-radio>
          <el-radio v-model="updateData.type" label="1">按钮</el-radio>
          <el-radio v-model="updateData.type" label="2">接口</el-radio>
          <el-radio v-model="updateData.type" label="3">页面</el-radio>
        </el-form-item>
        <el-form-item label="名称" prop="name">
          <el-input v-model="updateData.name" placeholder="请输入名称" />
        </el-form-item>

        <el-form-item v-if="updateData.type === '1' " label="按钮" prop="btn_key">
          <el-input v-model="updateData.btn_key" placeholder="请输入按钮权限" />
        </el-form-item>
        <el-form-item v-if="updateData.type === '0' " label="图标" prop="icon">
          <el-input v-model="updateData.icon" placeholder="请输入图标" />
        </el-form-item>

        <template v-if="updateData.type === '0' || updateData.type === '3' ">
          <el-form-item label="组件" prop="component">
            <el-input v-model="updateData.component" placeholder="请输入组件" />
          </el-form-item>
          <el-form-item label="路由" prop="router">
            <el-input v-model="updateData.router" placeholder="请输入路由" />
          </el-form-item>
        </template>

        <el-form-item v-if="updateData.type === '2'" label="路由" prop="router">
          <el-select v-model="updateData.router" placeholder="请选择" @focus="getPermissionsAll">
            <el-option
              v-for="(v,k) in permissionsAll"
              :key="k"
              :label="v.router+'【'+v.method+'】'"
              :value="v.router+','+v.method"
            />
          </el-select>
        </el-form-item>

        <el-form-item label="排序" prop="sort">
          <el-slider v-model="updateData.sort" />
        </el-form-item>
      </el-form>

      <div slot="footer" class="dialog-footer">
        <el-button @click="updateVisible = false">取 消</el-button>
        <el-button type="primary" :loading="load" @click="updateSubmit('updateForm')">确 定</el-button>
      </div>
    </el-dialog>

  </div>
</template>

<script>
import {
  permissionsIntersection,
  permissionsFather,
  permissionsStore,
  permissionsUpdate,
  permissionsDel,
  permissionsIndex,
  permissionsAll
} from '@/api/permission'
import { btnPermission } from '@/utils/btnPermission'
export default {
  name: 'Permission',
  data() {
    return {
      btnPer: {
        create: false,
        update: false,
        delete: false
      },
      tableData: {
        data: [],
        total: 0 // 总条数

      },
      page: 0, // 当前页
      limit: 15, // 每页显示
      query: {
        keyword: ''
      },
      // 路由交集（后台路由）
      permissionsIntersection: [],
      // 父级路由
      permissionsFather: [],
      // 所有路由
      permissionsAll: [],
      role: [], // 所有角色
      loading: true,
      selectIds: [], // 选中id
      createVisible: false,
      load: false,
      createData: {
        type: '0',
        icon: '',
        btn_key: '',
        component: '',
        pid: '',
        name: '',
        router: '',
        method: '',
        sort: 0
      },
      updateData: {
        id: '',
        type: '',
        icon: '',
        btn_key: '',
        component: '',
        pid: '',
        name: '',
        router: '',
        method: '',
        sort: ''
      },
      rules: {
      },
      updateVisible: false
    }
  },
  created() {
    const role = this.$route.meta.role
    this.btnPer.create = btnPermission(role, 'create')
    this.btnPer.update = btnPermission(role, 'update')
    this.btnPer.delete = btnPermission(role, 'delete')
    this.getData()
  },
  methods: {
    getData() {
      const params = {
        keyword: this.query.keyword,
        page: this.page,
        limit: this.limit
      }
      permissionsIndex(params).then(res => {
        if (res.code === 200) {
          this.tableData = res.data
          this.loading = false
        }
      })
    },
    // 路由交集（后台）
    getPermissionsIntersection() {
      permissionsIntersection().then(res => {
        if (res.code === 200) {
          this.permissionsIntersection = res.data
        }
      })
    },
    // 父级路由
    getPermissionsFather() {
      permissionsFather().then(res => {
        if (res.code === 200) {
          this.permissionsFather = res.data
        }
      })
    },
    getPermissionsAll() {
      permissionsAll().then(res => {
        if (res.code === 200) {
          this.permissionsAll = res.data
        }
      })
    },
    // 添加
    createSubmit(refName) {
      this.$refs[refName].validate((valid) => {
        if (valid) {
          this.$confirm('确认提交吗？', '提示', {}).then(() => {
            this.load = true
            permissionsStore(this.createData)
              .then((res) => {
                if (res.code === 200) {
                  this.load = false
                  this.$notify({
                    message: '添加成功',
                    type: 'success',
                    duration: 1000
                  })
                  this.$refs[refName].resetFields()
                  this.createVisible = false
                  this.getData()
                }
              }).catch(err => {
                this.load = false
              })
          })
        }
      })
    },
    updateSubmit(refName) {
      this.$refs[refName].validate((valid) => {
        if (valid) {
          this.$confirm('确认提交吗？', '提示', {}).then(() => {
            this.load = true
            permissionsUpdate(this.updateData)
              .then((res) => {
                if (res.code === 200) {
                  this.load = false
                  this.$notify({
                    message: '修改成功',
                    type: 'success',
                    duration: 1000
                  })
                  this.$refs[refName].resetFields()
                  this.updateVisible = false
                  this.getData()
                }
              }).catch(err => {
                this.load = false
              })
          })
        }
      })
    },
    // 删除操作
    handleDelete(index, row) {
      // 二次确认删除
      this.$confirm('确定要删除吗？', '提示', {
        type: 'warning'
      })
        .then(() => {
          this.apiDel(row.id)
        })
        .catch(() => {})
    },
    // 接口删除
    apiDel(id) {
      permissionsDel(id)
        .then((res) => {
          if (res.code === 200) {
            this.$notify({
              message: '删除成功',
              type: 'success',
              duration: 1000
            })
            this.getData()
          }
        })
    },
    // 编辑
    handleEdit(index, row) {
      this.getPermissionsFather()
      this.updateVisible = true
      this.updateData = Object.assign({},
        {
          id: row.id,
          icon: row.icon,
          name: row.name,
          type: row.type + '',
          btn_key: row.btn_key,
          component: row.component,
          pid: row.pid,
          router: row.router,
          method: row.method,
          sort: row.sort
        }
      )
    },
    // 触发搜索按钮
    handleSearch() {
      this.loading = true
      this.getData()
    },
    // 多选操作
    handleSelectionChange(val) {
      console.log(val)
      this.selectIds = val
    },
    // 批量删除
    delAllSelection() {
      var ids = this.selectIds.map(item => item.id).toString()
      if (ids.length === 0) {
        this.$notify({
          message: '请选择',
          type: 'error',
          duration: 1000
        })
        return
      }
      this.$confirm('确定要删除吗？', '提示', {
        type: 'warning'
      })
        .then(() => {
          this.apiDel(ids)
        })
        .catch(() => {
        })
    },
    // 页码跳转
    handleCurrentChange(val) {
      this.loading = true
      this.page = val
      this.getData()
    },
    // 条数不一样
    handleSizeChange(val) {
      this.loading = true
      this.limit = val
      this.getData()
    }
  }
}
</script>

<style scoped>
.searchBox {
  display: flex;
}
.btnMb{
  margin-bottom: 20px;
}
</style>
