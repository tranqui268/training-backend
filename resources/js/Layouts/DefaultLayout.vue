<template>
    <div>
        <nav class="nav-bar">
            <div class="left-nav">
                <Link href="/dashboard">Dashboard</Link>
                <Link href="/user-manager" @click.native="() => console.log('click Users')">Users</Link>
            </div>
            <div class="right-nav">
               <span>Xin chào, {{ auth.user.name }}</span>
               <button @click="handleLogout" class="logout-btn">
                    Đăng xuất
               </button>
            </div>
        </nav>

        <main class="p-6">
            <slot></slot>
        </main>
    </div>
</template>
<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'

const { props } = usePage()
const auth = props.auth

const handleLogout = () => {
    router.post('/logout')
}
</script>
<style scoped>
.nav-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.left-nav {
    display: flex;
    align-items: center;
    gap: 20px;
}

.right-nav {
    display: flex;
    align-items: center;
    gap: 15px;
}

.logout-btn {
   padding: 8px 16px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}
</style>