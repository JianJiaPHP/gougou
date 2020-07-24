<template>
  <div class="app-container">
    <el-row :gutter="10" class="btn-bt btnMb" type="flex">
        <el-button  type="primary" size="medium" @click="backupssql">立即备份</el-button>
        <el-button  type="primary" size="medium" @click="optimization">优化表</el-button>
        <el-button  type="primary" size="medium" @click="repair">修复表</el-button>
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
<!--      <el-table-column prop="id" label="ID" width="55" align="center" hidden/>-->
      <el-table-column prop="Name" label="表名" align="center" />
      <el-table-column  label="数据量" align="center" >
        <template slot-scope="scope">
         【{{scope.row.Rows}}】条记录
        </template>
      </el-table-column>
      <el-table-column prop="Data_length" label="数据大小" align="center" />
      <el-table-column prop="Create_time" label="创建时间" align="center" />
      <el-table-column  label="备份状态" align="center" >
      <template slot-scope="scope">
        等待备份...
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
  </div>
</template>

<script>
import {
  index,
  optimization,
  repair,
  backupssql
} from '@/api/data'
import { getToken } from '@/utils/auth'
import { upload, appUrl } from '@/utils/'
import { btnPermission } from '@/utils/btnPermission'
import { CalcuMD5 } from '@/utils'
import {administratorsStore} from "@/api/permission";
export default {
  name: 'Administrators',
  data() {
    return {
      btnPer: {
        create: false,
        update: false,
        delete: false
      },
      status: [
        { type: 'success', label: '正常' },
        { type: 'info', label: '移除' },
      ],
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
        keyword: '',
        appointment_startTime:''
      },
      role: [], // 所有角色
      loading: true,
      selectIds: [], // 选中id
      createVisible: false,
      load: false,
      createData: {
        startTimeList: '',//结束预约时间
        name: '',//申请人姓名
        thing: '',//事由
        reportingTime: '',//时长
        sort: '',//权重
      },
      imgCreateUrl: '',
      imgUpdateUrl: '',
      updateData: {
        id: '',
        startTimeList: '',//结束预约时间
        mobileNumber: '',//手机号
        name: '',//申请人姓名
        thing: '',//事由
        reportingTime: '',//时长
        sort: '',//权重
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
    optimization(refName){
          var ids = this.selectIds.map(item => item.Name).toString()
          if (ids.length === 0) {
            this.$notify({
              message: '请选择',
              type: 'error',
              duration: 1000
            })
            return
          }
          this.$confirm('确认优化表吗？', '提示', {}).then(() => {
            this.load = true
            optimization({tables:ids})
              .then((res) => {
                if (res.code === 200) {
                  this.load = false
                  this.$notify({
                    message: '优化成功',
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
    },
    repair(refName){
          var ids = this.selectIds.map(item => item.Name).toString()
          if (ids.length === 0) {
            this.$notify({
              message: '请选择',
              type: 'error',
              duration: 1000
            })
            return
          }
          this.$confirm('确认修复表吗？', '提示', {}).then(() => {
            this.load = true
            repair({tables:ids})
              .then((res) => {
                if (res.code === 200) {
                  this.load = false
                  this.$notify({
                    message: '修复成功',
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
    },
    backupssql(refName){
          this.$confirm('确认备份数据库吗？', '提示', {}).then(() => {
            this.load = true
            backupssql({})
              .then((res) => {
                if (res.code === 200) {
                  this.load = false
                  this.$notify({
                    message: '备份成功',
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
    },
    getData() {
      const params = {
      }
      index(params).then(res => {
        if (res.code === 200) {
          console.log(res.data)
          this.tableData = res.data
          this.loading = false
        }
      })
    },
    // 编辑
    handleEdit(index, row) {
      this.updateVisible = true
      this.updateData = Object.assign({}, {
        id: row.id,
        startTimeList: [row.appointment_startTime,row.appointment_endTime],//结束预约时间
        mobileNumber: row.mobileNumber,//手机号
        name: row.name,//申请人姓名
        thing: row.thing,//事由
        reportingTime: row.reportingTime,//时长
        sort: row.sort,//时长
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

