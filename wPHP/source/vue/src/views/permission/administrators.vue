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
        <el-button v-if="btnPer.create" type="primary" size="medium" @click="createVisible = true">添加</el-button>
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
      <el-table-column prop="account" label="账号" align="center" />
      <el-table-column prop="nickname" label="昵称" align="center" />
      <el-table-column prop="avatar" label="头像" align="center">
        <el-avatar slot-scope="scope" fit="scale-down" :src="scope.row.avatar" />
      </el-table-column>
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

    <!--添加管理员-->
    <el-dialog title="添加管理员" :visible.sync="createVisible">
      <el-form ref="createForm" :model="createData" :rules="rules" label-width="auto">
        <el-form-item label="账号" prop="account">
          <el-input v-model="createData.account" placeholder="请输入账号（登陆名）" />
        </el-form-item>

        <el-form-item label="昵称" prop="nickname">
          <el-input v-model="createData.nickname" placeholder="请输入昵称" />
        </el-form-item>
        <el-form-item label="头像" prop="avatar">
          <el-upload
            class="avatar-uploader"
            :action="uploads"
            :show-file-list="false"
            :headers="myHeaders"
            :on-success="handleAvatarSuccess"
          >
            <img v-if="createData.avatar" :src="createData.avatar" class="avatar">
            <i v-else class="el-icon-plus avatar-uploader-icon" />
          </el-upload>
        </el-form-item>
        <el-form-item label="密码" prop="password">
          <el-input v-model="createData.password" placeholder="请输入角色描述" />
        </el-form-item>
        <el-form-item label="角色" prop="role_id">
          <el-select v-model="createData.role_id" placeholder="请选择" @focus="getRolesAll">
            <el-option
              v-for="(v,k) in role"
              :key="k"
              :label="v.name"
              :value="v.id"
            />
          </el-select>
        </el-form-item>

      </el-form>

      <div slot="footer" class="dialog-footer">
        <el-button @click="createVisible = false">取 消</el-button>
        <el-button type="primary" :loading="load" @click="createSubmit('createForm')">确 定</el-button>
      </div>
    </el-dialog>
    <!--修改管理员-->
    <el-dialog title="修改管理员" :visible.sync="updateVisible">
      <el-form ref="updateForm" :model="updateData" :rules="rules" label-width="auto">
        <el-form-item label="账号" prop="account">
          <el-input v-model="updateData.account" placeholder="请输入账号（登陆名）" />
        </el-form-item>

        <el-form-item label="昵称" prop="nickname">
          <el-input v-model="updateData.nickname" placeholder="请输入昵称" />
        </el-form-item>
        <el-form-item label="头像" prop="avatar">
          <el-upload
            class="avatar-uploader"
            :action="uploads"
            :headers="myHeaders"
            :show-file-list="false"
            :on-success="updateHandleAvatarSuccess"
          >
            <img v-if="updateData.avatar" :src="updateData.avatar" class="avatar">
            <i v-else class="el-icon-plus avatar-uploader-icon" />
          </el-upload>
        </el-form-item>
        <el-form-item label="密码" prop="password">
          <el-input v-model="updateData.password" placeholder="请输入密码" />
        </el-form-item>
        <el-form-item label="角色" prop="role_id">
          <el-select v-model="updateData.role_id" placeholder="请选择">
            <el-option
              v-for="(v,k) in role"
              :key="k"
              :label="v.name"
              :value="v.id"
            />
          </el-select>
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
  administrators,
  rolesAll,
  administratorsStore,
  administratorsUpdate,
  administratorsDel

} from '@/api/permission'
import { getToken } from '@/utils/auth'
import { upload, appUrl } from '@/utils/'
import { btnPermission } from '@/utils/btnPermission'
import { CalcuMD5 } from '@/utils'
export default {
  name: 'Administrators',
  data() {
    return {
      btnPer: {
        create: false,
        update: false,
        delete: false
      },
      myHeaders: {
        Authorization: 'Bearer ' + getToken()
      },
      uploads: upload,
      tableData: {
        data: [],
        total: 0 // 总条数
      },
      page: 0, // 当前页
      limit: 15, // 每页显示
      query: {
        keyword: ''
      },
      role: [], // 所有角色
      loading: true,
      selectIds: [], // 选中id
      createVisible: false,
      load: false,
      createData: {
        account: '',
        nickname: '',
        avatar: '',
        password: '',
        role_id: ''
      },
      imgCreateUrl: '',
      imgUpdateUrl: '',
      updateData: {
        id: '',
        nickname: '',
        account: '',
        avatar: '',
        password: '',
        role_id: ''
      },
      rules: {
        avatar: [{ required: true, message: '请上传图片', trigger: 'blur' }],
        role_id: [{ required: true, message: '请选择角色', trigger: 'blur' }],
        nickname: [
          { max: 20, message: '管理员昵称名字在20位以内', trigger: 'blur' },
          { required: true, message: '请输入管理员昵称', trigger: 'blur' }
        ],
        account: [
          { max: 20, message: '管理员账号在20位以内', trigger: 'blur' },
          { required: true, message: '请输入管理员账号', trigger: 'blur' }
        ],
        password: [
          { min: 6, max: 16, message: '密码在1 ~ 16 位', trigger: 'blur' },
          { required: true, message: '请输入密码', trigger: 'blur' }
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
      administrators(params).then(res => {
        if (res.code === 200) {
          this.tableData = res.data
          this.loading = false
        }
      })
    },
    // 获取所有路由
    getRolesAll() {
      rolesAll().then(res => {
        if (res.code === 200) {
          this.role = res.data
        }
      })
    },
    // 添加上传头像后
    handleAvatarSuccess(res) {
      this.createData.avatar = res.data
      // this.imgCreateUrl = appUrl + res.data
    },

    updateHandleAvatarSuccess(res) {
      this.updateData.avatar = res.data
      // this.imgUpdateUrl = appUrl + res.data
    },

    // 储存
    createSubmit(refName) {
      this.$refs[refName].validate((valid) => {
        if (valid) {
          this.$confirm('确认提交吗？', '提示', {}).then(() => {
            this.load = true
            administratorsStore(this.createData)
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
            administratorsUpdate(this.updateData)
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
      administratorsDel(id)
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
      this.getRolesAll()
      this.updateVisible = true
      this.updateData = Object.assign({}, {
        id: row.id,
        account: row.account,
        nickname: row.nickname,
        avatar: row.avatar,
        role_id: row.role_id
      })
      this.imgUpdateUrl = row.avatar
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

<style >
.searchBox {
  display: flex;
}
.btnMb{
  margin-bottom: 20px;
}
.avatar-uploader .el-upload {
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}
.avatar-uploader .el-upload:hover {
  border-color: #409EFF;
}
.avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 178px;
  height: 178px;
  line-height: 178px;
  text-align: center;
}
.avatar {
  width: 178px;
  height: 178px;
  display: block;
}
</style>
