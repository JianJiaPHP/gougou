<template>
  <div class="app-container">

    <el-row :gutter="10" class="btn-bt" type="flex">
      <el-col :span="6">
        <el-input v-model="query.keyword" size="medium" placeholder="关键字" />
      </el-col>
      <el-col :span="18">
        <el-button type="primary" size="medium" icon="el-icon-search" @click="handleSearch">搜索</el-button>
        <el-button v-if="btnPer.create" type="primary" size="medium" @click="createVisible = true">添加</el-button>
      </el-col>
    </el-row>

    <el-table
      ref="multipleTable"
      v-loading="loading"
      :data="tableData.data"
      border
      class="table btn-bt"
      header-cell-class-name="table-header"
    >
      <el-table-column prop="id" label="ID" width="55" align="center" />
      <el-table-column prop="key" label="key" align="center" />
      <el-table-column prop="value" show-overflow-tooltip label="值" align="center" />
      <el-table-column prop="desc" show-overflow-tooltip label="描述" align="center" />
      <el-table-column prop="created_at" label="添加时间" align="center" />
      <el-table-column label="操作" align="center">
        <template slot-scope="scope">
          <el-button
            v-if="btnPer.update"
            type="text"
            icon="el-icon-edit"
            @click="handleEdit(scope.$index, scope.row)"
          >编辑
          </el-button>
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

    <!--添加-->
    <el-dialog title="添加配置" :visible.sync="createVisible">
      <el-form ref="createForm" :model="createData" :rules="rules" label-width="auto">
        <el-form-item label="key" prop="key">
          <el-input v-model="createData.key" placeholder="请输入key" />
        </el-form-item>
        <el-form-item label="描述" prop="desc">
          <el-input v-model="createData.desc" placeholder="请输入描述" />
        </el-form-item>

        <el-form-item label="value" prop="value">
          <el-input
            v-model="createData.value"
            type="textarea"
            rows="10"
            placeholder="请输入内容"
          />
        </el-form-item>
      </el-form>

      <div slot="footer" class="dialog-footer">
        <el-button @click="createVisible = false">取 消</el-button>
        <el-button type="primary" :loading="load" @click="createSubmit('createForm')">确 定</el-button>
      </div>
    </el-dialog>
    <!--修改-->
    <el-dialog title="修改配置" :visible.sync="updateVisible">
      <el-form ref="updateForm" :model="updateData" :rules="rules" label-width="auto">
        <el-form-item label="key" prop="key">
          <el-input v-model="updateData.key" placeholder="请输入key" />
        </el-form-item>
        <el-form-item label="描述" prop="desc">
          <el-input v-model="updateData.desc" placeholder="请输入描述" />
        </el-form-item>

        <el-form-item label="value" prop="value">
          <el-input
            v-model="updateData.value"
            type="textarea"
            rows="10"
            placeholder="请输入内容"
          />
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
import { config, configStore, configUpdate } from '@/api/system'
import { btnPermission } from '@/utils/btnPermission'
export default {
  name: 'Config',
  data() {
    return {
      btnPer: {
        create: false,
        update: false
      },
      tableData: {
        data: [],
        total: 0 // 总条数
      },
      page: 1, // 当前页
      limit: 15, // 每页显示
      query: {
        keyword: ''
      },
      loading: true,
      createVisible: false,
      load: false,
      createData: {
        key: '',
        value: '',
        desc: ''
      },
      updateVisible: false,
      updateData: {
        id: '',
        key: '',
        value: '',
        desc: ''
      },
      rules: {
        key: [
          { required: true, message: '请输入key', trigger: 'blur' }
        ],
        desc: [
          { required: true, message: '请输入描述', trigger: 'blur' }
        ],
        value: [
          { required: true, message: '请输入value', trigger: 'blur' }
        ]

      }
    }
  },
  created() {
    const role = this.$route.meta.role
    this.btnPer.create = btnPermission(role, 'create')
    this.btnPer.update = btnPermission(role, 'update')
    this.getData()
  },
  methods: {
    // 获取配置
    getData() {
      const params = {
        keyword: this.query.keyword,
        page: this.page,
        limit: this.limit
      }
      config(params)
        .then(res => {
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
        key: row.key,
        value: row.value,
        desc: row.desc
      })
    },

    createSubmit(refName) {
      this.$refs[refName].validate((valid) => {
        if (valid) {
          this.$confirm('确认提交吗？', '提示', {}).then(() => {
            this.load = true
            configStore(this.createData)
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
            configUpdate(this.updateData)
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
                // eslint-disable-next-line handle-callback-err
              }).catch(err => {
                this.load = false
              })
          })
        }
      })
    },

    // 触发搜索按钮
    handleSearch() {
      this.page = 1
      this.loading = true
      this.getData()
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
  .btn-bt{
    margin-bottom: 20px;
  }
</style>
