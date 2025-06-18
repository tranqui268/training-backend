<template>
  <div class="p-6 max-w-xl mx-auto">
    <div class="bg-white shadow-md rounded-2xl p-6">
      <h3 class="text-xl font-semibold text-gray-800 mb-4">Thông tin tài khoản</h3>

      <div class="mb-3 flex items-center">
        <label class="w-40 text-gray-600 font-medium">Tên:</label>
        <span class="text-gray-800">{{ user.name }}</span>
      </div>

      <div class="mb-3 flex items-center">
        <label class="w-40 text-gray-600 font-medium">Email:</label>
        <span class="text-gray-800">{{ user.email }}</span>
      </div>

      <div class="mb-3 flex items-center">
        <label class="w-40 text-gray-600 font-medium">Số điện thoại:</label>
        <span class="text-gray-800">{{ user.phone || 'Chưa cập nhật' }}</span>
      </div>

      <div class="mb-3 flex items-center">
        <label class="w-40 text-gray-600 font-medium">Lần đăng nhập cuối:</label>
        <span class="text-gray-800">{{ formatDate(user.last_login_at) }}</span>
      </div>

      <div class="flex items-center">
        <label class="w-40 text-gray-600 font-medium">IP cuối:</label>
        <span class="text-gray-800">{{ user.last_login_ip || 'N/A' }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue';

defineOptions({
    layout: DefaultLayout
})

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
})

function formatDate(dateString) {
    if (!dateString) return 'Chưa đăng nhập'
    const date = new Date(dateString)
    return date.toLocaleDateString('vi-VN', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    })
}
</script>
