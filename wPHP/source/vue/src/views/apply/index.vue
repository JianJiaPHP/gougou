<template>
  <div class="app-container">
    <el-row :gutter="10" class="btn-bt btnMb" type="flex">
      <el-col :span="6" class="searchBox">
        <el-input v-model="query.keyword" size="medium" placeholder="关键字" />
      </el-col>
<!--      <el-col :span="10">-->
<!--        <div class="block">-->
<!--          <el-date-picker-->
<!--            v-model="query.appointment_startTime"-->
<!--            type="datetimerange"-->
<!--            value-format="yyyy-MM-dd HH:mm:ss"-->
<!--            range-separator="至"-->
<!--            start-placeholder="预约开始日期"-->
<!--            end-placeholder="预约结束日期"-->
<!--            align="right">-->
<!--          </el-date-picker>-->
<!--        </div>-->
<!--      </el-col>-->
      <el-col :span="8">
        <div class="block">
          <el-date-picker
            v-model="query.created_at"
            type="date"
            value-format="yyyy-MM-dd"
            placeholder="选择申请日期">
          </el-date-picker>
        </div>
      </el-col>
      <el-col>
        <el-button
          type="primary"
          size="medium"
          icon="el-icon-search"
          @click="handleSearch"
        >搜索</el-button>
      </el-col>
        <el-button v-if="btnPer.create" type="primary" size="medium" @click="createVisible = true">添加</el-button>
<!--        <el-button v-if="btnPer.delete" type="danger" size="medium" @click="delAllSelection">删除</el-button>-->
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
      <el-table-column prop="name" label="访客人员" align="center" />
<!--      <el-table-column  label="访客人员联系方式" align="center" >-->
<!--        <template slot-scope="scope">-->
<!--          <p v-if="scope.row.list"> {{scope.row.list.mobileNumber}}</p>-->
<!--        </template>-->
<!--      </el-table-column>-->
      <el-table-column prop="reportingTime" label="汇报时长（分钟）" align="center" />
      <el-table-column prop="thing" label="汇报事由" align="center" />
      <el-table-column prop="created_at" label="申请时间" align="center" />
<!--      <el-table-column    width="340" prop="appointment_startTime - appointment_endTime" label="预约时间" align="center" >-->
<!--          <template slot-scope="scope">-->
<!--                   {{scope.row.appointment_startTime}} - {{scope.row.appointment_endTime}}-->
<!--          </template>-->
<!--      </el-table-column>-->
      <el-table-column prop="status" label="状态" align="center" >
        <template slot-scope="scope">
          <div class="tag-group">
            <el-tag  :type="scope.row.status==1 ?'success':'info'">
              {{ scope.row.status==1 ?'正常':'移除' }}
            </el-tag>
          </div>
        </template>
      </el-table-column>
      <el-table-column prop="created_at" label="添加时间" align="center" />
      <el-table-column label="操作" align="center">
        <template slot-scope="scope">
          <el-button v-if="btnPer.update" type="text" icon="el-icon-edit" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
          <el-button
            v-if="scope.row.status==1"
            type="text"
            icon="el-icon-delete"
            class="red"
            @click="updateStatus(scope.$index, scope.row)"
          >移除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <!--修改预约-->
    <el-dialog title="修改预约" :visible.sync="updateVisible">
      <el-form ref="updateForm" :model="updateData" :rules="rules" label-width="auto">
        <el-form-item label="权重序号" prop="sort">
          <el-input v-model="updateData.sort" placeholder="请输入权重序号（越大越靠前）" />
        </el-form-item>
        <el-form-item label="访客人员姓名" prop="name" >
          <el-input v-model="updateData.name" placeholder="请输入昵称"/>
        </el-form-item>
        <el-form-item label="汇报时长（分钟）" prop="reportingTime">
          <el-input v-model="updateData.reportingTime" placeholder="请输入汇报时长（分钟）" />
        </el-form-item>
<!--        <el-form-item label="预约时间区间" prop="appointment_startTime">-->
<!--          <div class="block">-->
<!--            <el-date-picker-->
<!--              v-model="updateData.startTimeList"-->
<!--              type="datetimerange"-->
<!--              range-separator="至"-->
<!--              value-format="yyyy-MM-dd HH:mm:ss"-->
<!--              start-placeholder="开始日期"-->
<!--              end-placeholder="结束日期"-->
<!--              align="right">-->
<!--            </el-date-picker>-->
<!--          </div>-->

<!--        </el-form-item>-->
        <el-form-item label="事由" prop="thing">
          <el-input type="textarea" :rows="7"  placeholder="请输入事由" v-model="updateData.thing">
          </el-input>
        </el-form-item>

      </el-form>

      <div slot="footer" class="dialog-footer">
        <el-button @click="updateVisible = false">取 消</el-button>
        <el-button type="primary" :loading="load" @click="updateSubmit('updateForm')">确 定</el-button>
      </div>
    </el-dialog>
    <!--修改预约end-->


    <!--添加预约-->
    <el-dialog title="修改预约" :visible.sync="createVisible">
      <el-form ref="createForm" :model="createData" :rules="createVis" label-width="auto">
        <el-form-item label="权重序号" prop="sort">
          <el-input v-model="createData.sort" placeholder="请输入权重序号（越大越靠前）" />
        </el-form-item>
        <el-form-item label="访客人员姓名" prop="name" >
          <el-input v-model="createData.name" placeholder="请输入昵称"/>
        </el-form-item>
        <el-form-item label="汇报时长（分钟）" prop="reportingTime">
          <el-input v-model="createData.reportingTime" placeholder="请输入汇报时长（分钟）" />
        </el-form-item>
<!--        <el-form-item label="预约时间区间" prop="appointment_startTime">-->
<!--          <div class="block">-->
<!--            <el-date-picker-->
<!--              v-model="createData.startTimeList"-->
<!--              type="datetimerange"-->
<!--              range-separator="至"-->
<!--              value-format="yyyy-MM-dd HH:mm:ss"-->
<!--              start-placeholder="开始日期"-->
<!--              end-placeholder="结束日期"-->
<!--              align="right">-->
<!--            </el-date-picker>-->
<!--          </div>-->

<!--        </el-form-item>-->
        <el-form-item label="事由" prop="thing">
          <el-input type="textarea" :rows="7"  placeholder="请输入事由" v-model="createData.thing">
          </el-input>
        </el-form-item>

      </el-form>

      <div slot="footer" class="dialog-footer">
        <el-button @click="createVisible = false">取 消</el-button>
        <el-button type="primary" :loading="load" @click="createSubmit('createForm')">确 定</el-button>
      </div>
    </el-dialog>
    <!--添加预约end-->



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
  updateSubmit,
  createSubmit,
  updateStatus
} from '@/api/apply'
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
        appointment_startTime:'',
        appointment_endTime:'',
        created_at:''
      },
      role: [], // 所有角色
      loading: true,
      selectIds: [], // 选中id
      createVisible: false,
      load: false,
      createData: {
        startTimeList: ['',''],//结束预约时间
        name: '',//申请人姓名
        thing: '',//事由
        reportingTime: '',//时长
        sort: '',//权重
      },
      imgCreateUrl: '',
      imgUpdateUrl: '',
      updateData: {
        id: '',
        startTimeList: ['',''],//结束预约时间
        mobileNumber: '',//手机号
        name: '',//申请人姓名
        thing: '',//事由
        reportingTime: '',//时长
        sort: '',//权重
      },
      rules: {
        sort: [{ required: true, message: '请输入权重', trigger: 'blur' }],
        reportingTime: [{ required: true, message: '请输入时长（分钟）', trigger: 'blur' }],
        // startTimeList: [{ required: true, message: '请输入时长区间', trigger: 'blur' }],
        thing: [{ required: true, message: '请输入事由', trigger: 'blur' }],
        name: [{ required: true, message: '请输入名字', trigger: 'blur' }],
        // nickname: [
        //   { max: 20, message: '管理员昵称名字在20位以内', trigger: 'blur' },
        //   { required: true, message: '请输入管理员昵称', trigger: 'blur' }
        // ],
        // account: [
        //   { max: 20, message: '管理员账号在20位以内', trigger: 'blur' },
        //   { required: true, message: '请输入管理员账号', trigger: 'blur' }
        // ],
        // password: [
        //   { min: 6, max: 16, message: '密码在1 ~ 16 位', trigger: 'blur' },
        //   { required: true, message: '请输入密码', trigger: 'blur' }
        // ]
      },
      createVis: {
        sort: [{ required: true, message: '请输入权重', trigger: 'blur' }],
        reportingTime: [{ required: true, message: '请输入时长（分钟）', trigger: 'blur' }],
        // startTimeList: [{ required: true, message: '请输入时长区间', trigger: 'blur' }],
        thing: [{ required: true, message: '请输入事由', trigger: 'blur' }],
        name: [{ required: true, message: '请输入名字', trigger: 'blur' }],
        // nickname: [
        //   { max: 20, message: '管理员昵称名字在20位以内', trigger: 'blur' },
        //   { required: true, message: '请输入管理员昵称', trigger: 'blur' }
        // ],
        // account: [
        //   { max: 20, message: '管理员账号在20位以内', trigger: 'blur' },
        //   { required: true, message: '请输入管理员账号', trigger: 'blur' }
        // ],
        // password: [
        //   { min: 6, max: 16, message: '密码在1 ~ 16 位', trigger: 'blur' },
        //   { required: true, message: '请输入密码', trigger: 'blur' }
        // ]
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
    // 更新预约
    updateSubmit(refName){
      this.$refs[refName].validate((valid) => {
        if (valid) {
          this.$confirm('确认提交吗？', '提示', {}).then(() => {
            this.load = true
            updateSubmit(this.updateData)
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
    // 更新预约
    createSubmit(refName){
      this.$refs[refName].validate((valid) => {
        if (valid) {
          this.$confirm('确认提交吗？', '提示', {}).then(() => {
            this.load = true
            createSubmit(this.createData)
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
    updateStatus(index,row){
      let status = row.status ? 10 : 1;
          this.$confirm('确认移除吗？', '提示', {}).then(() => {
            this.load = true
            updateStatus(row.id,status)
              .then((res) => {
                if (res.code === 200) {
                  this.$notify({
                    message: '移除成功',
                    type: 'success',
                    duration: 1000
                  })
                  this.getData()
                }
              }).catch(err => {
              this.load = false
            })
          })
    },
    getData() {
      const params = {
        ...this.query,
        page: this.page,
        limit: this.limit
      }
      index(params).then(res => {
        if (res.code === 200) {
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
        startTimeList: [row.appointment_startTime?row.appointment_startTime:'',row.appointment_endTime?row.appointment_endTime:''],//结束预约时间
        name: row.name,//申请人姓名
        thing: row.thing,//事由
        reportingTime: row.reportingTime,//时长
        sort: row.sort,//时长
      })
      console.log(this.updateData)
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

