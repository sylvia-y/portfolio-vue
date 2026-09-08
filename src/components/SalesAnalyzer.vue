<script setup>
import { ref, nextTick } from 'vue'
import {
  Chart,
  LineController,
  LineElement,
  PointElement,
  CategoryScale,
  LinearScale,
  BarController,
  BarElement,
  Tooltip,
  Legend,
} from 'chart.js'

Chart.register(
  LineController,
  LineElement,
  PointElement,
  CategoryScale,
  LinearScale,
  BarController,
  BarElement,
  Tooltip,
  Legend
)

const file = ref(null)
const result = ref(null)
const loading = ref(false)

const dailyChart = ref(null)
const productChart = ref(null)

let dailyChartInstance = null
let productChartInstance = null

const handleFileChange = (event) => {
  file.value = event.target.files[0]
}

const createCharts = async () => {
  await nextTick()

  if (!result.value) return

  // 기존 차트가 있다면 삭제
  dailyChartInstance?.destroy()
  productChartInstance?.destroy()

  // 날짜별 매출 차트
  dailyChartInstance = new Chart(dailyChart.value, {
    type: 'line',
    data: {
      labels: result.value.daily_sales.map(item => item.date),
      datasets: [
        {
          label: 'Daily Sales',
          data: result.value.daily_sales.map(item => item.sales),
          tension: 0.3,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: true,
        },
      },
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  })

  // 상품별 매출 차트
  productChartInstance = new Chart(productChart.value, {
    type: 'bar',
    data: {
      labels: result.value.product_sales.map(item => item.product),
      datasets: [
        {
          label: 'Product Sales',
          data: result.value.product_sales.map(item => item.sales),
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: true,
        },
      },
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  })
}

const analyzeSales = async () => {
  if (!file.value) {
    alert('CSV 파일을 선택해주세요.')
    return
  }

  loading.value = true

  try {
    const formData = new FormData()
    formData.append('file', file.value)

    const response = await fetch('http://127.0.0.1:8000/analyze', {
      method: 'POST',
      body: formData,
    })

    if (!response.ok) {
      throw new Error('분석 요청에 실패했습니다.')
    }

    result.value = await response.json()

    await createCharts()
  } catch (error) {
    console.error(error)
    alert('분석 중 오류가 발생했습니다.')
  } finally {
    loading.value = false
  }
}
</script>
<template>
  <section class="sales-analyzer">
    <h2>AI Sales Analyzer</h2>

    <input
      type="file"
      accept=".csv"
      @change="handleFileChange"
    >

    <button
      type="button"
      class="analyze-btn"
      @click="analyzeSales"
      :disabled="loading"
    >
      {{ loading ? '분석 중...' : '분석하기' }}
    </button>

    <div v-if="result" class="result">
      <h3>분석 결과</h3>

      <p>
        총 매출:
        {{ result.summary.total_sales.toLocaleString() }}원
      </p>

      <p>
        주문 건수:
        {{ result.summary.order_count }}건
      </p>

      <p>
        판매 수량:
        {{ result.summary.total_quantity }}개
      </p>

      <p>
        평균 주문 금액:
        {{ result.summary.average_order.toLocaleString() }}원
      </p>

      <div class="chart-box">
        <h3>날짜별 매출</h3>
        <canvas ref="dailyChart"></canvas>
      </div>

      <div class="chart-box">
        <h3>상품별 매출</h3>
        <canvas ref="productChart"></canvas>
      </div>
    </div>
  </section>
</template>