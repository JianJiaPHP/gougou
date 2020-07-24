<template>
  <div class="app-container">
    <div >
      <el-row :gutter="24">
        <el-col :span="24" :xs="24">
          <el-card>
            <div slot="header" class="clearfix">
              <span>领导信息</span>
            </div>
            <el-tabs>
                <el-form ref="updateForm" :model="updateData" :rules="updateVis" label-width="auto">
<!--                  <el-form-item label="姓名" prop="name" >-->
<!--                    <el-input v-model.trim="updateData.name"/>-->
<!--                  </el-form-item>-->
                  <el-form-item label="手机号" prop="phone">
                    <el-input v-model.trim="updateData.phone" />
                  </el-form-item>
                  <el-form-item>
                    <el-button  type="primary" :loading="load" @click="submit('updateForm')">更新</el-button>
                  </el-form-item>
                </el-form>
            </el-tabs>
          </el-card>
        </el-col>
      </el-row>
    </div>
  </div>
</template>
<script>
import {
  index,
  submit
} from '@/api/boss'
import { getToken } from '@/utils/auth'
import { btnPermission } from '@/utils/btnPermission'

export default {
  name: 'index',
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
      role: [], // 所有角色
      loading: true,
      selectIds: [], // 选中id
      load: false,
      updateData: {
        id: '',//id
        name: '',//领导姓名
        phone: '',//领导手机号
      },
      imgCreateUrl: '',
      imgUpdateUrl: '',
      updateVis: {
        // name: [{required: true, message: '请输入名字', trigger: 'blur'}],
        phone: [
          { max: 16, min:11 , message: '手机号为11位', trigger: 'blur' },
          { required: true, message: '请输入手机号', trigger: 'blur' }
        ],
      },
      updateVisible: true
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
    submit(refName) {
      this.$refs[refName].validate((valid) => {
        if (valid) {
          this.$confirm('确认修改吗？', '提示', {}).then(() => {
            this.load = true
            submit(this.updateData)
              .then((res) => {
                if (res.code === 200) {
                  this.load = false
                  this.$notify({
                    message: '修改成功',
                    type: 'success',
                    duration: 1000
                  })
                  this.$refs[refName].resetFields()
                  this.getData()
                }
              }).catch(err => {
              this.load = false
            })
          })
        }
      })
    },
    getData() {
      const params = {
        page: this.page,
        limit: this.limit
      }
      index(params).then(res => {
        if (res.code === 200) {
          this.updateData = res.data
          this.loading = false
        }
      })
    },
  }
}
</script>
<style lang="scss" scoped>
  .box-center {
    margin: 0 auto;
    display: table;
  }

  .text-muted {
    color: #777;
  }

  .user-profile {
  .user-name {
    font-weight: bold;
  }

  .box-center {
    padding-top: 10px;
  }

  .user-role {
    padding-top: 10px;
    font-weight: 400;
    font-size: 14px;
  }

  .box-social {
    padding-top: 30px;

  .el-table {
    border-top: 1px solid #dfe6ec;
  }
  }

  .user-follow {
    padding-top: 20px;
  }
  }

  .user-bio {
    margin-top: 20px;
    color: #606266;

  span {
    padding-left: 4px;
  }

  .user-bio-section {
    font-size: 14px;
    padding: 15px 0;

  .user-bio-section-header {
    border-bottom: 1px solid #dfe6ec;
    padding-bottom: 10px;
    margin-bottom: 10px;
    font-weight: bold;
  }
  }
  }
</style>
