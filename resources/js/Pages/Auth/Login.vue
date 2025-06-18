<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-md">
      <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Đăng nhập</h2>

      <form @submit.prevent="handleSubmit">
        <!-- Email -->
        <div class="mb-4">
          <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            :class="[
              'w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2',
              errors.email ? 'border-red-500 focus:ring-red-200' : 'border-gray-300 focus:ring-blue-200'
            ]"
            placeholder="Nhập email của bạn"
            required
          />
          <p v-if="errors.email" class="text-sm text-red-500 mt-1">{{ errors.email }}</p>
        </div>

        <!-- Password -->
        <div class="mb-4">
          <label for="password" class="block text-gray-700 font-medium mb-1">Mật khẩu</label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            :class="[
              'w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2',
              errors.password ? 'border-red-500 focus:ring-red-200' : 'border-gray-300 focus:ring-blue-200'
            ]"
            placeholder="Nhập mật khẩu"
            required
          />
          <p v-if="errors.password" class="text-sm text-red-500 mt-1">{{ errors.password }}</p>
        </div>

        <!-- Remember Me -->
        <div class="mb-4 flex items-center">
          <input
            id="remember"
            v-model="form.remember"
            type="checkbox"
            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
          />
          <label for="remember" class="ml-2 block text-sm text-gray-700">
            Ghi nhớ đăng nhập
          </label>
        </div>

        <!-- Auth Error -->
        <div v-if="errors.auth" class="mb-4">
          <p class="text-sm text-red-500">{{ errors.auth }}</p>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="form.processing"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-md transition disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <span v-if="form.processing" class="animate-spin mr-2 border-t-2 border-white rounded-full w-4 h-4 inline-block"></span>
          {{ form.processing ? 'Đang đăng nhập...' : 'Đăng nhập' }}
        </button>
      </form>

      <!-- Footer -->
      <div class="mt-6 text-center text-sm text-gray-600">
        <p>
          Chưa có tài khoản?
          <Link href="/register" class="text-blue-600 hover:underline">Đăng ký ngay</Link>
        </p>
      </div>
    </div>
  </div>
</template>


<script setup>
import { useForm, Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const emit = defineEmits(['success', 'error']);

const props = defineProps({
  errors: {
    type: Object,
    default: () => ({})
  }
});

const page = usePage();

const form = useForm({
  email: '',
  password: '',
  remember: false
});

function handleSubmit() {
  form.post('/login', {
    onSuccess: () => {
      const flashSuccess = page.props.flash?.success;
      if (flashSuccess) {
        emit('success', flashSuccess);
      }
    },
    onError: () => {
      emit('error', 'Có lỗi xảy ra, vui lòng thử lại');
    }
  });
}
</script>


<style scoped>
.login-form {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 80vh;
  padding: 20px;
}

.form-container {
  background: white;
  padding: 40px;
  border-radius: 10px;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
}

.form-container h2 {
  text-align: center;
  margin-bottom: 30px;
  color: #333;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  color: #555;
  font-weight: 500;
}

.form-group input[type="email"],
.form-group input[type="password"] {
  width: 100%;
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 16px;
  transition: border-color 0.3s;
  box-sizing: border-box;
}

.form-group input[type="email"]:focus,
.form-group input[type="password"]:focus {
  outline: none;
  border-color: #007bff;
}

.form-group input.error {
  border-color: #dc3545;
}

.checkbox-group label {
  display: flex;
  align-items: center;
  cursor: pointer;
}

.checkbox-group input[type="checkbox"] {
  margin-right: 10px;
}

.error-message {
  color: #dc3545;
  font-size: 14px;
  margin-top: 5px;
  display: block;
}

.submit-btn {
  width: 100%;
  padding: 12px;
  background: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  cursor: pointer;
  transition: background-color 0.3s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  box-sizing: border-box;
}

.submit-btn:hover:not(:disabled) {
  background: #0056b3;
}

.submit-btn:disabled {
  background: #6c757d;
  cursor: not-allowed;
}

.spinner {
  width: 16px;
  height: 16px;
  border: 2px solid #ffffff;
  border-top: 2px solid transparent;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.form-footer {
  text-align: center;
  margin-top: 20px;
}

.form-footer a {
  color: #007bff;
  text-decoration: none;
}

.form-footer a:hover {
  text-decoration: underline;
}
</style>