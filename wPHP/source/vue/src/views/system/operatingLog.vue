<template>
  <div class="app-container">

    <el-row :gutter="10" class="btn-bt" type="flex">
      <el-col :span="6">
        <el-input v-model="query.keyword" size="medium" class="w300" placeholder="关键字" />
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
      <el-table-column prop="router" label="操作路径" align="center" />
      <el-table-column prop="method" label="操作方式" align="center" />
      <el-table-column show-overflow-tooltip="true" prop="content" label="操作内容" align="center" />
      <el-table-column prop="desc" label="操作描述" align="center" />
      <el-table-column prop="ip" label="操作ip" align="center" />
      <el-table-column label="操作人" align="center">
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
import { operatingLog } from '@/api/system'
export default {
  name: 'OperatingLog',
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
      loading: true,
      load: false
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
      operatingLog(params)
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
