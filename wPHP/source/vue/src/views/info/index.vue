<template>
  <div class="app-container">
    <div v-if="user">
      <el-row :gutter="20">

        <el-col :span="6" :xs="24">
          <el-card style="margin-bottom:20px;">
            <div slot="header" class="clearfix">
              <span>个人信息</span>
            </div>

            <div class="user-profile">
              <div class="box-center">
                <pan-thumb :image="user.avatar" :height="'100px'" :width="'100px'" :hoverable="false">
                  <div>Hello</div>
                  {{ user.role }}
                </pan-thumb>
              </div>
              <div class="box-center">
                <div class="user-name text-center">{{ user.name }}</div>
                <div class="user-role text-center text-muted">{{ user.role | uppercaseFirst }}</div>
              </div>
            </div>

            <div class="user-bio">
              <div class="user-education user-bio-section">
                <div class="user-bio-section-header">账号：<span>{{ user.account }}</span></div>
                <div class="user-bio-section-header">昵称：<span>{{ user.name }}</span></div>
              </div>
            </div>
          </el-card>
        </el-col>

        <el-col :span="18" :xs="24">
          <el-card>
            <el-tabs v-model="activeTab">
              <el-tab-pane label="密码" name="activity">
                <el-form ref="passwordForm" :model="password" :rules="passwordRules">
                  <el-form-item label="原密码" prop="old_password">
                    <el-input v-model.trim="password.old_password" />
                  </el-form-item>
                  <el-form-item label="新密码" prop="password">
                    <el-input v-model.trim="password.password" />
                  </el-form-item>
                  <el-form-item label="确认密码" prop="password_confirmation">
                    <el-input v-model.trim="password.password_confirmation" />
                  </el-form-item>
                  <el-form-item>
                    <el-button v-if="btnPer.update" type="primary" :loading="load" @click="submit('passwordForm')">更新</el-button>
                  </el-form-item>
                </el-form>
              </el-tab-pane>
            </el-tabs>
          </el-card>
        </el-col>

      </el-row>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import PanThumb from '@/components/PanThumb'
import { updatePassword } from '@/api/user'
import { btnPermission } from '@/utils/btnPermission'
export default {
  name: 'Info',
  components: { PanThumb },
  data() {
    var validNewPwd = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请输入密码'))
      } else {
        callback()
      }
    }
    var validConPwd = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请再次输入密码'))
      } else if (value !== this.password.password) {
        callback(new Error('两次输入密码不一致!'))
      } else {
        callback()
      }
    }
    return {
      btnPer: {
        update: false
      },
      user: {},
      activeTab: 'activity',
      load: false,
      password: {
        old_password: '',
        password: '',
        password_confirmation: ''
      },
      passwordRules: {
        old_password: [
          { min: 6, max: 16, message: '密码在 6 到 16 个字符', trigger: 'blur' },
          { required: true, message: '请输入原密码', trigger: 'blur' }
        ],
        password: [
          { min: 6, max: 16, message: '密码在 6 到 16 个字符', trigger: 'blur' },
          { validator: validNewPwd, required: true, trigger: 'blur' }
        ],
        password_confirmation: [
          { validator: validConPwd, required: true, trigger: 'blur' }
        ]
      }
    }
  },
  computed: {
    ...mapGetters([
      'name',
      'avatar',
      'roles',
      'account'
    ])
  },
  created() {
    const role = this.$route.meta.role
    this.btnPer.update = btnPermission(role, 'update')
    this.getUser()
  },
  methods: {
    getUser() {
      this.user = {
        name: this.name,
        role: this.roles.join(' | '),
        avatar: this.avatar,
        account: this.account
      }
    },
    submit(refName) {
      this.$refs[refName].validate((valid) => {
        if (valid) {
          this.$confirm('确认提交吗？', '提示', {}).then(() => {
            this.load = true
            updatePassword(this.password)
              .then((res) => {
                if (res.code === 200) {
                  this.load = false
                  this.$notify({
                    message: '修改成功',
                    type: 'success',
                    duration: 1000
                  })
                  this.$refs[refName].resetFields()
                  this.$router.push(`/login`)
                  this.$store.dispatch('user/logout')
                }
                // eslint-disable-next-line handle-callback-err
              }).catch(err => {
                this.load = false
              })
          })
        }
      })
    }
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
