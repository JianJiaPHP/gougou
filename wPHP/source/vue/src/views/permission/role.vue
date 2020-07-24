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
        <el-button v-if="btnPer.create" type="primary" size="medium" @click="createRole">添加</el-button>
        <el-button v-if="btnPer.delete" type="danger" size="medium" @click="delAllSelection">删除</el-button>
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
      <el-table-column prop="name" label="名称" align="center" />
      <el-table-column prop="desc" label="描述" align="center" />
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
        :current-page="page"
        :page-size="limit"
        layout="total, sizes, prev, pager, next, jumper"
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
      />
    </div>

    <el-dialog title="添加角色" :visible.sync="createVisible">
      <el-form ref="createForm" :model="createData" :rules="rules" label-width="auto">
        <el-form-item label="权限" prop="permission">
          <el-tree
            ref="createTree"
            :data="permission"
            show-checkbox
            default-expand-all
            node-key="id"
            highlight-current
            :props="defaultProps"
          />
        </el-form-item>

        <el-form-item label="角色名" prop="name">
          <el-input v-model="createData.name" placeholder="请输入角色名" />
        </el-form-item>
        <el-form-item label="角色描述" prop="desc">
          <el-input v-model="createData.desc" placeholder="请输入角色描述" />
        </el-form-item>

      </el-form>

      <div slot="footer" class="dialog-footer">
        <el-button @click="createVisible = false">取 消</el-button>
        <el-button type="primary" :loading="load" @click="createSubmit('createForm')">确 定</el-button>
      </div>
    </el-dialog>
    <!--添加角色-->
    <el-dialog title="添加角色" :visible.sync="createVisible">
      <el-form ref="createForm" :model="createData" :rules="rules" label-width="auto">
        <el-form-item label="权限" prop="permission">
          <el-tree
            ref="createTree"
            :data="permission"
            show-checkbox
            default-expand-all
            node-key="id"
            highlight-current
            :props="defaultProps"
          />
        </el-form-item>

        <el-form-item label="角色名" prop="name">
          <el-input v-model="createData.name" placeholder="请输入角色名" />
        </el-form-item>
        <el-form-item label="角色描述" prop="desc">
          <el-input v-model="createData.desc" placeholder="请输入角色描述" />
        </el-form-item>

      </el-form>

      <div slot="footer" class="dialog-footer">
        <el-button @click="createVisible = false">取 消</el-button>
        <el-button type="primary" :loading="load" @click="createSubmit('createForm')">确 定</el-button>
      </div>
    </el-dialog>
    <!--修改角色-->
    <el-dialog title="修改角色" :visible.sync="updateVisible">
      <el-form ref="updateForm" :model="updateData" :rules="rules" label-width="auto">
        <el-form-item label="权限" prop="permission">
          <el-tree
            ref="tree"
            :data="permission"
            show-checkbox
            default-expand-all
            node-key="id"
            highlight-current
            :check-strictly="true"
            :default-checked-keys="updateData.permission"
            :props="defaultProps"
          />
        </el-form-item>

        <el-form-item label="角色名" prop="name">
          <el-input v-model="updateData.name" placeholder="请输入角色名" />
        </el-form-item>
        <el-form-item label="角色描述" prop="desc">
          <el-input v-model="updateData.desc" placeholder="请输入角色描述" />
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
  rolesIndex,
  permissionsTree,
  rolesStore,
  rolesUpdate,
  rolesDel
} from '@/api/permission'
import { btnPermission } from '@/utils/btnPermission'
export default {
  name: 'Role',
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
      permission: [], // 权限
      defaultProps: {
        children: 'children',
        label: 'name'
      },
      loading: true,
      selectIds: [], // 选中id
      createVisible: false,
      load: false,
      createData: {
        name: '',
        desc: '',
        permission: ''
      },
      updateData: {
        id: '',
        name: '',
        desc: '',
        permission: ''
      },
      rules: {
        name: [
          { max: 50, message: '角色名在50位内', trigger: 'blur' },
          { required: true, message: '请输入角色名', trigger: 'blur' }
        ],
        desc: [
          { max: 100, message: '角色描述在100 位内', trigger: 'blur' },
          { required: true, message: '请输入角色描述', trigger: 'blur' }
        ]
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
      rolesIndex(params).then(res => {
        if (res.code === 200) {
          this.tableData = res.data
          this.loading = false
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
      rolesDel(id)
        .then((res) => {
          if (res.code === 200) {
            this.$notify({
              message: '删除成功',
              type: 'success',
              duration: 1000
            })
            this.getData()
          }
        }).catch(err => {
          this.load = false
        })
    },
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
    // 添加
    createRole() {
      this.createVisible = true
      this.getPermissionsTree()
    },

    // 储存
    createSubmit(refName) {
      this.$refs[refName].validate((valid) => {
        if (valid) {
          this.$confirm('确认提交吗？', '提示', {}).then(() => {
            this.load = true
            const permission = this.$refs.createTree.getCheckedKeys().concat(this.$refs.createTree.getHalfCheckedKeys())
            this.createData.permission = permission.join(',')
            rolesStore(this.createData)
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
    // 更新
    updateSubmit(refName) {
      this.$refs[refName].validate((valid) => {
        if (valid) {
          this.$confirm('确认提交吗？', '提示', {}).then(() => {
            this.load = true
            const permission = this.$refs.tree.getCheckedKeys().concat(this.$refs.tree.getHalfCheckedKeys())
            this.updateData.permission = permission.join(',')
            rolesUpdate(this.updateData)
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

    // 获取权限树
    getPermissionsTree() {
      permissionsTree().then(res => {
        if (res.code === 200) {
          this.permission = res.data
        }
      })
    },
    // 编辑
    handleEdit(index, row) {
      this.getPermissionsTree()
      this.updateVisible = true
      this.updateData = Object.assign({}, {
        id: row.id,
        name: row.name,
        desc: row.desc,
        permission: row.permission
      })
    },

    // 触发搜索按钮
    handleSearch() {
      this.page = 1
      this.loading = true
      this.getData()
    },
    // 多选操作
    handleSelectionChange(val) {
      this.selectIds = val
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
