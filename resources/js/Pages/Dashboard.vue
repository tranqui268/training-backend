<template>
    <div class="content">
        <div class="user-card">
            <h3>Thông tin tài khoản</h3>

            <div class="info-row">
                <label>Tên:</label>
                <span>{{ user.name }}</span>
            </div>

            <div class="info-row">
                <label>Email:</label>
                <span>{{ user.email }}</span>
            </div>

            <div class="info-row">
                <label>Số điện thoại:</label>
                <span>{{ user.phone || 'Chưa cập nhật' }}</span>
            </div>

            <div class="info-row">
                <label>Lần đăng nhập cuối:</label>
                <span>{{ formatDate(user.last_login_at) }}</span>
            </div>

            <div class="info-row">
                <label>IP cuối:</label>
                <span>{{ user.last_login_ip || 'N/A' }}</span>
            </div>
        </div>
    </div>
</template>
<script>
import DefaultLayout from '@/Layouts/DefaultLayout.vue';

export default {
    layout: DefaultLayout,

    name: 'Dashboard',

    props: {
        user: {
            type: Object,
            required: true
        }
    },

    methods: {
        formatDate(dateString) {
            if (!dateString) return 'Chưa đăng nhập';
            const date = new Date(dateString);
            return date.toLocaleDateString('vi-VN', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    }
}
</script>
<style scoped>
.user-card {
    border-top: 1px solid #eee;
    padding-top: 1.5rem;
}

.user-card h3 {
    color: #333;
    margin-bottom: 1rem;
}

.info-row{
    margin-bottom: 0.75rem;
    padding: 0.5rem;
    background: #f8f9fa;
    border-radius: 4px;
}
</style>