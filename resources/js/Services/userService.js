import apiClient from "@/plugins/axios";

export default {
    async fetchUsers() {
        try {
            const response = await apiClient.get("/users", { withCredentials: true });
            return response.data;
        } catch (error) {
            alert("Error fetching users:", error);
            console.error("Error fetching users:", error);
            throw error;
        }
    },

    async getUserById(){
        
    }
}