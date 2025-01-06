import { defineStore } from "pinia";
import axios from "axios";

export const useTodoListStore = defineStore("todoList", {
    state: () => ({
        todos: [],
        notifications: [],
    }),
    actions: {
        async fetchTodos() {
            try {
                const response = await axios.get("/todos");
                this.todos = response.data;
            } catch (error) {
                this.showNotification(error.response.data.message);
            }
        },
        addNewLine() {
            this.todos.push({
                id: null,
                title: "",
                description: "",
                status: "To do",
                isCreating: true,
                isDeleted: false,
            });
        },
        async addTodo(todo) {
            try {
                const response = await axios.post("/todos", todo);
                const indexOfLastElement = this.todos.length - 1;
                this.todos[indexOfLastElement] = response.data.todo;
                this.showNotification(response.data.message);
            } catch (error) {
                this.showNotification(error.response.data.message);
            }
        },
        async updateTodo(todo) {
            try {
                const response = await axios.put(`/todos/${todo.id}`, todo);
                this.showNotification(response.data.message);
            } catch (error) {
                this.showNotification(error.response.data.message);
            }
        },
        async deleteTodo(id) {
            try {
                const response = await axios.delete(`/todos/${id}`);
                this.todos = this.todos.filter((todo) => todo.id !== id);
                this.showNotification(response.data.message);
            } catch (error) {
                this.showNotification(error.response.data.message);
            }
        },
        showNotification(message) {
            if (message) {
                this.notifications.push(message);
                setTimeout(() => {
                    this.notifications.shift();
                }, 3000);
            }
        },
    }
});
