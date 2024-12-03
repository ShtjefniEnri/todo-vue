<template>
    <div class="container">
        <h1 class="font-bold mb-3">Todo List</h1>
        <table class="w-full">
            <thead>
            <tr>
                <th class="border py-2">Title</th>
                <th class="border py-2">Description</th>
                <th class="border py-2">Status</th>
                <th class="border py-2">Action</th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="(todo, index) in todos"
                @focusout="handleRowBlur(todo, index)"
                :key="todo.id"
                class="hover:bg-gray-100"
            >
                <td class="border px-4 py-2">
                    <input
                        v-model="todo.title"
                        class="w-full border rounded px-2 py-1"
                        :autofocus="todo.isCreating"
                    />
                </td>
                <td class="border px-4 py-2">
                    <input
                        v-model="todo.description"
                        class="w-full border rounded px-2 py-1"
                    />
                </td>
                <td class="border px-4 py-2">
                    <select
                        v-model="todo.status"
                        class="w-full border rounded px-2 py-1"
                    >
                        <option value="To do">To do</option>
                        <option value="In progress">In progress</option>
                        <option value="Finished">Finished</option>
                    </select>
                </td>
                <td class="border px-4 py-2">
                    <button
                        @click="deleteTodo(todo.id)"
                    >
                        Delete
                    </button>
                </td>
            </tr>
            </tbody>
        </table>

        <button
            @click="addTodo"
            class="mt-4"
        >
            Add Todo
        </button>

        <div
            v-for="notification in notifications"
            class="fixed"
        >
            {{ notification }}
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            todos: [],
            notifications: [],
        };
    },
    name: 'ToDo',
    methods: {
        async fetchTodos() {
            const response = await axios.get("/todos");
            this.todos = response.data;
        },
        addTodo() {
            this.todos.push({
                id: null,
                title: "",
                description: "",
                status: "To do",
                isCreating: true,
            });

            this.$nextTick(() => {
                document.querySelector('input[autofocus]').focus();
            });
        },
        async handleRowBlur(todo, index) {
            if (!this.todos.includes(todo)) {
                return;
            }

            if (todo.isCreating && !todo.id) {
                if (!todo.title.trim()) {
                    this.todos.splice(index, 1);
                    return;
                }

                try {
                    const response = await axios.post("/todos", {
                        title: todo.title,
                        description: todo.description,
                        status: todo.status,
                    });

                    todo.isCreating = false;
                    this.todos[index] = response.data.todo;
                    this.showNotification(response.data.message);
                } catch (error) {
                    this.showNotification(error.response.data.message);
                }

            }
            if (!todo.isCreating && todo.id) {
                try {
                    const response = await axios.put(`/todos/${todo.id}`, todo);
                    this.showNotification(response.data.message);
                } catch (error) {
                    this.showNotification(error.response.data.message);
                }
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
    },
    mounted() {
        this.fetchTodos();
    },
};
</script>
