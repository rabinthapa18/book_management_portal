<template>
    <h1>{{ heading }}</h1>
    <div class="table-actions">
        <v-row justify="start">
            <!-- delete button -->
            <v-col cols="12" md="2" class="mb-2">
                <v-btn rounded="lg" block prepend-icon="mdi-delete" :disabled="selectedBooks.length === 0">
                    Delete</v-btn>
            </v-col>
            <!-- edit button -->
            <v-col cols="12" md="2" class="mb-2">
                <v-btn rounded="lg" block prepend-icon="mdi-pencil" :disabled="selectedBooks.length !== 1">
                    Edit</v-btn>
            </v-col>
            <!-- spacer -->
            <v-col cols="12" md="5" class="mb-2">
            </v-col>
            <!-- sort dropdown -->
            <v-col cols="12" md="3" class="mb-3">
                <v-menu>
                    <template v-slot:activator="{ props }">
                        <v-btn v-bind="props" rounded="lg" block prepend-icon="mdi-sort" @click="sortDesc = !sortDesc">
                            Sort by
                        </v-btn>
                    </template>
                    <v-list>
                        <v-list-item @click="sortBooks('title', 'asc')">
                            <v-list-item-title>Title Acending</v-list-item-title>
                        </v-list-item>
                        <v-list-item @click="sortBooks('title', 'desc')">
                            <v-list-item-title>Title Descending</v-list-item-title>
                        </v-list-item>
                        <v-list-item @click="sortBooks('author', 'asc')">
                            <v-list-item-title>Author Ascending</v-list-item-title>
                        </v-list-item>
                        <v-list-item @click="sortBooks('author', 'desc')">
                            <v-list-item-title>Author Descending</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-col>
        </v-row>
    </div>

    <div class="table-container">
        <table style="user-select: none">
            <thead>
                <tr>
                    <th>
                        <!-- checkbox to select/unselect all books -->
                        <input type="checkbox" @change="toggleAllSelection" v-model="selectAll"
                            :disabled="books.length === 0" />
                    </th>
                    <th>S. No.</th>
                    <th>Title</th>
                    <th>Genre</th>
                    <th>Author</th>
                </tr>
            </thead>
            <!-- show loading spinner when fetching books -->
            <v-progress-circular v-if="isFetching" color="primary" indeterminate></v-progress-circular>
            <!-- show message when no books are found -->
            <div v-if="books.length === 0 && !isFetching">
                <br />
                <h5 style="
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
          ">
                    No books found
                </h5>
            </div>

            <!--  using table row component to render each book -->
            <tbody>
                <TableRow v-for="book in books.slice(start, end)" :key="book.id" :book="book"
                    :isSelected="selectedBooks.includes(book.id)" @update-selection="updateSelection" />
            </tbody>
        </table>
    </div>
</template>

<script>
import TableRow from "./TableRow.vue";

export default {
    name: "HomePage",
    components: {
        TableRow
    },
    props: {
        heading: String
    },
    data() {
        return {
            books: [],
            selectedBooks: [],
            selectAll: false,
            isFetching: false,
            sortItem: null,
            sortDesc: false,
        };
    },
    async mounted() {
        this.fetchBooks();
    },

    methods: {

        // select/unselect all books
        toggleAllSelection() {
            if (this.selectAll) {
                this.selectedBooks = this.books.map((book) => book.id);
            } else {
                this.selectedBooks = [];
            }
        },

        // select/unselect individual book
        updateSelection(bookId, isSelected) {
            if (isSelected) {
                this.selectedBooks.push(bookId);
            } else {
                this.selectedBooks = this.selectedBooks.filter(id => id !== bookId);
            }
            this.selectAll = this.selectedBooks.length === this.books.length;
        },

        // fetch books from API
        fetchBooks() {
            this.isFetching = true;

            // Simulating API call
            // TODO: Replace with actual API call
            setTimeout(() => {
                this.books = [
                    {
                        "id": "1",
                        "title": "To Kill a Mockingbird",
                        "genre": ["Classic", "Historical", "Fiction"],
                        "author": "Harper Lee"
                    },
                    {
                        "id": "2",
                        "title": "1984",
                        "genre": ["Dystopian", "Science Fiction", "Political Fiction"],
                        "author": "George Orwell"
                    },
                    {
                        "id": "3",
                        "title": "Pride and Prejudice",
                        "genre": ["Romance"],
                        "author": "Jane Austen"
                    },
                    {
                        "id": "4",
                        "title": "The Great Gatsby",
                        "genre": ["Classic", "Fiction"],
                        "author": "F. Scott Fitzgerald"
                    },
                    {
                        "id": "5",
                        "title": "Moby Dick",
                        "genre": ["Adventure", "Classic", "Fiction"],
                        "author": "Herman Melville"
                    }
                ];
                this.isFetching = false;
            }, 2000);
        },

        // sort books from API
        sortBooks(sortItem, sortingMethod) {
            // Simulating API for call
            // TODO: Replace with actual API call
            this.books.sort((a, b) => {
                let itemA = a[sortItem].toString().toLowerCase();
                let itemB = b[sortItem].toString().toLowerCase();

                if (sortingMethod === 'asc') {
                    return itemA < itemB ? -1 : itemA > itemB ? 1 : 0;
                } else if (sortingMethod === 'desc') {
                    return itemA > itemB ? -1 : itemA < itemB ? 1 : 0;
                }
                return 0;
            });
        }
    }
};
</script>

<style scoped>
@import "./HomePage.module.css";
</style>
