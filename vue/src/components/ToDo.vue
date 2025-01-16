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
                        @click="deleteTodo(todo)"
                    >
                        Delete
                    </button>
                </td>
            </tr>
            </tbody>
        </table>

        <button
            @click="newRow"
            class="mt-4 mr-2"
        >
            Add Todo
        </button>

        <button
            @click="exportTodos"
            class="mt-4 ml-2"
        >
            Export Todos
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
import { useTodoListStore } from "../stores/todoStore";
import { computed, nextTick } from "vue";

export default {
    setup() {
        const store = useTodoListStore();
        const todos = computed(() => store.todos);
        const notifications = computed(() => store.notifications);

        const exportTodos = () => {
            store.exportTodos();
        };

        const newRow = async () => {
            store.addNewLine();

            await nextTick();
            document.querySelector('input[autofocus]').focus();
        };

        const handleRowBlur = async (todo, index) => {
            if (todo.isCreating && !todo.id) {
                if (!todo.title.trim()) {
                    store.todos.splice(index, 1);
                    return;
                }

                await store.addTodo({
                    title: todo.title,
                    description: todo.description,
                    status: todo.status,
                });

                todo.isCreating = false;
            }

            if (!todo.isCreating && !todo.isDeleted && todo.id) {
                await store.updateTodo({
                    id: todo.id,
                    title: todo.title,
                    description: todo.description,
                    status: todo.status,
                });
            }
        };

        const deleteTodo = (todo) => {
            store.deleteTodo(todo.id);
            todo.isDeleted = true;
        };

        store.fetchTodos();

        return {
            todos,
            notifications,
            exportTodos,
            newRow,
            handleRowBlur,
            deleteTodo,
        };
    },
};
</script>
