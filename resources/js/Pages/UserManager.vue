<template>
    <div class="container">
        <h1>Quản lý tài khoản user</h1>

        <div v-if="loading" class="loading-container">
            <div class="spinner"></div>
            <p>Đang tải dữ liệu...</p>
        </div>

        <div v-else-if="error" class="error-container">
            <p class="error-message">{{ error }}</p>
            <button @click="retryFetch" class="btn btn-retry">Thử lại</button>
        </div>

        <div v-else-if="users && users.length > 0">
            <a-table :data-source="users" :columns="columns">
                <template #headerCell="{ column }">
                    <template v-if="column.key === 'name'">
                        <span style="color: #1890ff">Name</span>
                    </template>
                </template>
                <template #customFilterDropdown="{ setSelectedKeys, selectedKeys, confirm, clearFilters, column }">
                    <div style="padding: 8px">
                        <a-input ref="searchInput" :placeholder="`Search ${column.dataIndex}`" :value="selectedKeys[0]"
                            style="width: 188px; margin-bottom: 8px; display: block"
                            @change="e => setSelectedKeys(e.target.value ? [e.target.value] : [])"
                            @pressEnter="handleSearch(selectedKeys, confirm, column.dataIndex)" />
                        <a-button type="primary" size="small" style="width: 90px; margin-right: 8px"
                            @click="handleSearch(selectedKeys, confirm, column.dataIndex)">
                            <template #icon>
                                <SearchOutlined />
                            </template>
                            Search
                        </a-button>
                        <a-button size="small" style="width: 90px" @click="handleReset(clearFilters)">
                            Reset
                        </a-button>
                    </div>
                </template>
                <template #customFilterIcon="{ filtered }">
                    <search-outlined :style="{ color: filtered ? '#108ee9' : undefined }" />
                </template>
                <template #bodyCell="{ text, column, record }">
                    <span v-if="state.searchText && state.searchedColumn === column.dataIndex">
                        <template v-for="(fragment, i) in text
                            .toString()
                            .split(new RegExp(`(?<=${state.searchText})|(?=${state.searchText})`, 'i'))">
                            <mark v-if="fragment.toLowerCase() === state.searchText.toLowerCase()" :key="i"
                                class="highlight">
                                {{ fragment }}
                            </mark>
                            <template v-else>{{ fragment }}</template>
                        </template>
                    </span>
                    <template v-else-if="column.key === 'phone'">
                        <span v-if="record.phone">{{ record.phone }}</span>
                        <span v-else style="color: red;">Chưa cập nhật</span>
                    </template>

                    <template v-else-if="column.key === 'action'">
                        <a-button type="primary" @click="viewUserDetail(record)">Chi tiết</a-button>
                    </template>
                    <template v-else>
                        {{ text }}
                    </template>
                </template>
            </a-table>
        </div>

        <div v-else class="empty-container">
            <p>Không có dữ liệu người dùng</p>
            <button @click="retryFetch" class="btn btn-primary">Tải lại</button>
        </div>


        <div v-if="showUserDetail" class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chi tiết người dùng</h5>
                        <button type="button" class="close" @click="closeUserDetail">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div v-if="selectedUser" class="user-detail">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>ID:</strong></label>
                                        <p>{{ selectedUser.id }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Tên:</strong></label>
                                        <p>{{ selectedUser.name }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Email:</strong></label>
                                        <p>{{ selectedUser.email }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>Số điện thoại:</strong></label>
                                        <p>{{ selectedUser.phone || 'Chưa cập nhật' }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Ngày tạo:</strong></label>
                                        <p>{{ formatDate(selectedUser.created_at) }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Cập nhật lần cuối:</strong></label>
                                        <p>{{ formatDate(selectedUser.updated_at) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeUserDetail">Đóng</button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showUserDetail" class="modal-backdrop fade show"></div>
    </div>
</template>

<script setup>
import { SearchOutlined } from '@ant-design/icons-vue';
import { onMounted, ref, computed, watch, reactive } from 'vue';
import { useStore } from 'vuex';
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import { useRoute, onBeforeRouteUpdate } from 'vue-router';



defineOptions({
    layout: DefaultLayout,
    name: 'UserManager'
})


const store = useStore()
const users = computed(() => store.getters['userModel/users'])
const loading = computed(() => store.getters['userModel/loading'])
const error = computed(() => store.getters['userModel/error'])

const showUserDetail = ref(false)
const selectedUser = ref(null)
const refreshTimeout = ref(null)

const state = reactive({
    searchText: '',
    searchedColumn: '',
});
const searchInput = ref();

const columns = ref(
    [
        {
            title: 'Id',
            dataIndex: 'id',
            key: 'id'
        },
        {
            title: 'Name',
            dataIndex: 'name',
            key: 'name',
            customFilterDropdown: true,
            onFilter: (value, record) => record.name.toString().toLowerCase().includes(value.toLowerCase()),
            onFilterDropdownOpenChange: visible => {
                if (visible) {
                    setTimeout(() => {
                        searchInput.value.focus();
                    }, 100);
                }
            },
        },
        {
            title: 'Email',
            dataIndex: 'email',
            key: 'email',
            customFilterDropdown: true,
            onFilter: (value, record) => record.email.toString().toLowerCase().includes(value.toLowerCase()),
            onFilterDropdownOpenChange: visible => {
                if (visible) {
                    setTimeout(() => {
                        searchInput.value.focus();
                    }, 100);
                }
            },
        },
        {
            title: 'Phone',
            dataIndex: 'phone',
            key: 'phone',
        },
        {
            title: 'Action',
            key: 'action',
        }
    ]

)
const loadUsers = async () => {
    if (loading.value) {
        return
    }
    try {
        await store.dispatch('userModel/fetchUsers')
    } catch (error) {
        console.log('Error loading users: ', error)
    }
}

onMounted(() => {
    loadUsers()
})

watch(users, (newVal) => {
    console.log('user update: ', newVal)
})

const route = useRoute()
onBeforeRouteUpdate(async (to, from, next) => {
    console.log('beforeRouteUpdate - from:', from.path, 'to:', to.path)
    await loadUsers()
    next()
})

const retryFetch = async () => {
    try {
        await store.dispatch('userModel/fetchUsers')
    } catch (error) {
        console.error('Retry error:', error)
    }
}

const onRefreshData = () => {
    clearTimeout(refreshTimeout.value)
    refreshTimeout.value = setTimeout(() => {
        loadUsers()
    }, 1000)
}

const viewUserDetail = (record) => {
   console.log('viewUserDetail called with:', record) // Debug log
  selectedUser.value = record
  showUserDetail.value = true
  console.log('Modal state:', { showUserDetail: showUserDetail.value, selectedUser: selectedUser.value })
}

const closeUserDetail = () => {
    selectedUser.value = null
    showUserDetail.value = false
}

const formatDate = (dateString) => {
    if (!dateString) return 'N/A'
    const date = new Date(dateString)
    return date.toLocaleDateString('vi-VN') + ' ' + date.toLocaleTimeString('vi-VN')
}

const handleSearch = (selectedKeys, confirm, dataIndex) => {
    confirm();
    state.searchText = selectedKeys[0];
    state.searchedColumn = dataIndex;
};
const handleReset = clearFilters => {
    clearFilters({
        confirm: true,
    });
    state.searchText = '';
};
</script>

<style scoped>
.container {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

h1 {
    margin-bottom: 20px;
    font-size: 24px;
    color: #333;
}


.loading-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px;
    text-align: center;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 16px;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

.action-buttons {
    pointer-events: auto;
}

.action-buttons .btn {
    pointer-events: auto;
}

.error-container {
    text-align: center;
    padding: 40px;
}

.error-message {
    color: #e74c3c;
    margin-bottom: 16px;
    font-size: 16px;
}


.empty-container {
    text-align: center;
    padding: 40px;
    color: #666;
}


.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    margin: 0 4px;
    transition: all 0.2s ease;
}

.btn-primary {
    background-color: #3498db;
    color: white;
}

.btn-primary:hover {
    background-color: #2980b9;
}

.btn-danger {
    background-color: #e74c3c;
    color: white;
}

.btn-danger:hover {
    background-color: #c0392b;
}

.btn-retry {
    background-color: #95a5a6;
    color: white;
}

.btn-retry:hover {
    background-color: #7f8c8d;
}
</style>