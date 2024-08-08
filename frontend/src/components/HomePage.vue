<template>
    <h1>{{ heading }}</h1>

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
            isFetching: false
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
        }
    }
};
</script>

<style scoped>
@import "./HomePage.module.css";
</style>
