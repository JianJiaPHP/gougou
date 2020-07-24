<template>
  <div class="app-container">

    <el-row :gutter="10" class="btn-bt" type="flex">
      <el-col :span="6">
        <el-input v-model="query.keyword" size="medium" placeholder="关键字" />
      </el-col>
      <el-col :span="18">
        <el-button type="primary" size="medium" icon="el-icon-search" @click="handleSearch">搜索</el-button>
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
      <el-table-column prop="ip" label="ip地址" align="center" />
      <el-table-column prop="country" label="国家" align="center" />
      <el-table-column prop="region" label="区域" align="center" />
      <el-table-column prop="city" label="城市" align="center" />
      <el-table-column prop="county" label="县" align="center" />
      <el-table-column prop="isp" label="运营商" align="center" />
      <el-table-column label="管理员" align="center">
        <template slot-scope="scope">
          {{ scope.row.administrator.nickname }}
        </template>
      </el-table-column>
      <el-table-column prop="created_at" label="操作时间" align="center" />
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
import { loginLog } from '@/api/system'
export default {
  name: 'LoginLog',
  data() {
    return {
      tableData: {
        data: [],
        total: 0 // 总条数
      },
      page: 1,
      limit: 15,
      query: {
        keyword: ''
      },
      loading: true
    }
  },
  created() {
    this.getData()
  },
  methods: {
    getData() {
      const params = {
        keyword: this.query.keyword,
        page: this.page,
        limit: this.limit
      }
      loginLog(params)
        .then(res => {
          if (res.code === 200) {
            this.tableData = res.data
            this.loading = false
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
