<template>
  <div class="login-form">
    <div class="form-container">
      <h2>Đăng nhập</h2>
      
      <form @submit.prevent="handleSubmit">
        <div class="form-group">
          <label for="email">Email</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            :class="{ 'error': errors.email }"
            placeholder="Nhập email của bạn"
            required
          />
          <span v-if="errors.email" class="error-message">
            {{ errors.email }}
          </span>
        </div>

        <div class="form-group">
          <label for="password">Mật khẩu</label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            :class="{ 'error': errors.password }"
            placeholder="Nhập mật khẩu"
            required
          />
          <span v-if="errors.password" class="error-message">
            {{ errors.password }}
          </span>
        </div>

        <div class="form-group checkbox-group">
          <label>
            <input
              v-model="form.remember"
              type="checkbox"
            />
            <span class="checkmark"></span>
            Ghi nhớ đăng nhập
          </label>
        </div>

        <div v-if="errors.auth" class="form-group">
          <span class="error-message">{{ errors.auth }}</span>
        </div>

        <button
          type="submit"
          :disabled="form.processing"
          class="submit-btn"
        >
          <span v-if="form.processing" class="spinner"></span>
          {{ form.processing ? 'Đang đăng nhập...' : 'Đăng nhập' }}
        </button>
      </form>

      <div class="form-footer">
        <p>
          Chưa có tài khoản? 
          <Link href="/register">Đăng ký ngay</Link>
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