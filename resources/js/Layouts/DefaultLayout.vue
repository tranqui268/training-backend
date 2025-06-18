<template>
  <div>
    <nav class="flex items-center justify-between bg-white shadow px-6 py-4">
      <!-- Left Nav -->
      <div class="flex items-center space-x-4">
        <Link
          href="/dashboard"
          class="text-gray-700 hover:text-blue-600 font-medium transition"
        >
          Dashboard
        </Link>
        <Link
          href="/user-manager"
          @click.native="() => console.log('click Users')"
          class="text-gray-700 hover:text-blue-600 font-medium transition"
        >
          Users
        </Link>
      </div>

      <!-- Right Nav -->
      <div class="flex items-center space-x-4">
        <span class="text-gray-600">Xin chào, {{ auth.user.name }}</span>
        <button
          @click="handleLogout"
          class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-md font-medium transition"
        >
          Đăng xuất
        </button>
      </div>
    </nav>

    <main class="p-6 bg-gray-50 min-h-screen">
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