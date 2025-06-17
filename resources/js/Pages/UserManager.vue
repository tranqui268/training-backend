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
            <vue-bootstrap4-table :rows="users" :columns="columns" :config="config">
                <template slot="phone" slot-scope="props">
                    <span v-if="props.cell_value">{{ props.cell_value }}</span>
                    <span v-else class="text-danger font-italic">Chưa cập nhật</span>
                </template>

                <template slot="sort-asc-icon">
                    <i class="fas fa-sort-up"></i>
                </template>
                <template slot="sort-desc-icon">
                    <i class="fas fa-sort-down"></i>
                </template>
                <template slot="no-sort-icon">
                    <i class="fas fa-sort"></i>
                </template>

                <template slot="actions" slot-scope="props">
                    <div class="action-buttons">
                        <button @click.stop="viewUserDetail(props.row)" class="btn btn-sm btn-info mr-2"
                            title="Xem chi tiết">
                            <i class="fas fa-eye"></i> Chi tiết
                        </button>

                    </div>

                </template>

            </vue-bootstrap4-table>
        </div>

        <div v-else class="empty-container">
            <p>Không có dữ liệu người dùng</p>
            <button @click="retryFetch" class="btn btn-primary">Tải lại</button>
        </div>

        <!-- Modal xem chi tiết user -->
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

        <!-- Modal backdrop -->
        <div v-if="showUserDetail" class="modal-backdrop fade show"></div>
    </div>
</template>

<script>
import VueBootstrap4Table from 'vue-bootstrap4-table';
import { mapGetters, mapActions } from 'vuex';
import DefaultLayout from '@/Layouts/DefaultLayout.vue';

export default {
    layout: DefaultLayout,

    name: 'UserManager',

    data: function () {
        return {
            showUserDetail: false,
            selectedUser: null,
            columns: [{
                label: "id",
                name: "id",
                filter: {
                    type: "simple",
                    placeholder: "id"
                },
                sort: true,
            },
            {
                label: "First Name",
                name: "name",
                filter: {
                    type: "simple",
                    placeholder: "Enter name"
                },
                sort: true,
            },
            {
                label: "Email",
                name: "email",
                sort: true,
            },
            {
                label: "Phone",
                name: "phone",
                filter: {
                    type: "simple",
                    placeholder: "Enter phone"
                },
            },
            {
                label: "Hành động",
                name: "actions",
                sort: false,
            }],
            config: {
                checkbox_rows: true,
                rows_selectable: true,
                card_title: "Data"
            }
        };
    },

    computed: {
        ...mapGetters('userModel', ['users', 'loading', 'error']),
    },

    watch: {
        users(newVal) {
            console.log('users updated:', newVal);
        }
    },


    mounted() {
        this.loadUsers();

    },

    async beforeRouteEnter(to, from, next) {
        console.log('beforeRouteEnter - from:', from.path, 'to:', to.path);
        next(async vm => {
            await vm.loadUsers();
        });
    },

    methods: {
        ...mapActions('userModel', ['fetchUsers']),

        async loadUsers() {
            if (this.loading) {
                console.log('Already loading, skip...');
                return;
            }

            try {
                console.log('Loading users...');
                await this.fetchUsers();

            } catch (error) {
                console.error('Error loading users:', error);
            }
        },

        async retryFetch() {
            try {
                await this.fetchUsers();
            } catch (error) {
                console.error('Error retrying fetch users:', error);
            }
        },

        viewUserDetail(user) {
            this.selectedUser = user;
            this.showUserDetail = true;
            console.log('Viewing user detail:', user);
        },

        closeUserDetail() {
            this.showUserDetail = false;
            this.selectedUser = null;
        },

        formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('vi-VN') + ' ' + date.toLocaleTimeString('vi-VN');
        }

    },

    components: {
        VueBootstrap4Table
    },
}
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


.user-table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    border-radius: 8px;
    overflow: hidden;
}

.user-table thead {
    background-color: #f5f5f5;
    color: #333;
}

.user-table th,
.user-table td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.user-table tbody tr:hover {
    background-color: #f0f8ff;
    transition: background-color 0.2s ease;
}

.user-table th {
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
}

.user-table td {
    font-size: 14px;
    color: #444;
}


@media (max-width: 768px) {

    .user-table,
    .user-table thead,
    .user-table tbody,
    .user-table th,
    .user-table td,
    .user-table tr {
        display: block;
    }

    .user-table thead {
        display: none;
    }

    .user-table tr {
        margin-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        padding: 16px;
    }

    .user-table td {
        position: relative;
        padding-left: 50%;
        padding-bottom: 8px;
    }

    .user-table td::before {
        position: absolute;
        top: 8px;
        left: 16px;
        width: 45%;
        white-space: nowrap;
        font-weight: bold;
        color: #888;
        content: attr(data-label) ": ";
    }

    .user-table td:last-child {
        padding-left: 16px;
    }

    .user-table td:last-child::before {
        display: none;
    }
}
</style>