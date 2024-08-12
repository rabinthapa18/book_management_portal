<template>
  <h1>{{ heading }}</h1>
  <div class="actions">
    <v-row justify="start" no-gutters>
      <v-col cols="12" md="2">
        <v-menu>
          <template v-slot:activator="{ props }">
            <v-btn
              v-bind="props"
              rounded="0"
              block
              append-icon="mdi-menu-down"
              style="height: 100%"
            >
              {{ searchAttribute }}
            </v-btn>
          </template>
          <v-list>
            <v-list-item @click="setSearchAttribute('title')">
              <v-list-item-title>Title</v-list-item-title>
            </v-list-item>
            <v-list-item @click="setSearchAttribute('author')">
              <v-list-item-title>Author</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </v-col>
      <!-- search bar -->
      <v-col cols="12" md="7">
        <v-text-field
          :loading="loading"
          append-inner-icon="mdi-magnify"
          density="compact"
          :label="`Search by ${searchAttribute}`"
          variant="solo"
          hide-details
          single-line
          rounded="0"
          @click:append-inner="onClick"
        ></v-text-field>
      </v-col>
      <!-- spacer -->
      <v-col cols="12" md="1"> </v-col>
      <!-- export button -->
      <v-col cols="12" md="2">
        <v-btn
          rounded="0"
          block
          prepend-icon="mdi-file-export"
          style="height: 100%"
          >Export</v-btn
        >
      </v-col>
    </v-row>

    <!-- table actions -->
    <v-row justify="start">
      <!-- delete button -->
      <v-col cols="12" md="2" class="mb-2">
        <v-btn
          rounded="lg"
          block
          prepend-icon="mdi-delete"
          :disabled="selectedBooks.length === 0"
        >
          Delete</v-btn
        >
      </v-col>
      <!-- edit button -->
      <v-col cols="12" md="2" class="mb-2">
        <v-btn
          rounded="lg"
          block
          prepend-icon="mdi-pencil"
          :disabled="selectedBooks.length !== 1"
        >
          Edit</v-btn
        >
      </v-col>
      <!-- spacer -->
      <v-col cols="12" md="3" class="mb-2"> </v-col>
      <!-- sort dropdown -->
      <v-col cols="12" md="3" class="mb-3">
        <v-menu>
          <template v-slot:activator="{ props }">
            <v-btn v-bind="props" rounded="lg" block prepend-icon="mdi-sort">
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
      <v-col cols="12" md="2" class="mb-2">
        <!-- dialog to add a book -->
        <v-dialog max-width="500" persistent>
          <template v-slot:activator="{ props: activatorProps }">
            <v-btn
              v-bind="activatorProps"
              rounded="lg"
              block
              prepend-icon="mdi-plus"
              >Add</v-btn
            >
          </template>
          <template v-slot:default="{ isActive }">
            <v-card title="Add a book">
              <v-card-text>
                <v-text-field
                  label="Title"
                  outlined
                  clearable
                  v-model="title"
                ></v-text-field>
                <v-text-field
                  label="Author"
                  outlined
                  clearable
                  v-model="author"
                ></v-text-field>
                <v-text-field
                  label="Genre"
                  placeholder="Enter genres separated by comma"
                  outlined
                  clearable
                  v-model="genre"
                ></v-text-field>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn text="Cancel" @click="isActive.value = false"></v-btn>
              </v-card-actions>
            </v-card>
          </template>
        </v-dialog>
      </v-col>
    </v-row>
  </div>

  <div class="table-container">
    <table style="user-select: none">
      <thead>
        <tr>
          <th>
            <!-- checkbox to select/unselect all books -->
            <input
              type="checkbox"
              @change="toggleAllSelection"
              v-model="selectAll"
              :disabled="books.length === 0"
            />
          </th>
          <th>S. No.</th>
          <th>Title</th>
          <th>Genre</th>
          <th>Author</th>
        </tr>
      </thead>
      <!-- show loading spinner when fetching books -->
      <v-progress-circular
        v-if="isFetching"
        color="primary"
        indeterminate
      ></v-progress-circular>
      <!-- show message when no books are found -->
      <div v-if="books.length === 0 && !isFetching">
        <br />
        <h5
          style="
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
          "
        >
          No books found
        </h5>
      </div>

      <!--  using table row component to render each book -->
      <tbody>
        <TableRow
          v-for="book in books.slice(start, end)"
          :key="book.id"
          :book="book"
          :isSelected="selectedBooks.includes(book.id)"
          @update-selection="updateSelection"
        />
      </tbody>
    </table>
  </div>
</template>

<script>
import TableRow from "./TableRow.vue";

export default {
  name: "HomePage",
  components: {
    TableRow,
  },
  props: {
    heading: String,
  },
  data() {
    return {
      books: [],
      selectedBooks: [],
      selectAll: false,
      isFetching: false,
      searchAttribute: "author",
      searchTerm: "",
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
        this.selectedBooks = this.selectedBooks.filter((id) => id !== bookId);
      }
      this.selectAll = this.selectedBooks.length === this.books.length;
    },

    // fetch books from API
    async fetchBooks() {
      this.isFetching = true;

      // fetch books from API
      try {
        let response = await fetch(`${import.meta.env.VUE_API_URL}/books`);
        let data = await response.json();
        let books = data.books;
        // for each book, change the genre from string to array
        books.forEach((book) => {
          if (typeof book.genre === "string") {
            book.genre = JSON.parse(book.genre);
          }
        });

        this.books = books;
        console.log(this.books);
      } catch (error) {
        console.error(error);
      }
      this.isFetching = false;
    },

    // sort books from API
    sortBooks(sortItem, sortingMethod) {
      // Simulating API for call
      // TODO: Replace with actual API call
      this.books.sort((a, b) => {
        let itemA = a[sortItem].toString().toLowerCase();
        let itemB = b[sortItem].toString().toLowerCase();

        if (sortingMethod === "asc") {
          return itemA < itemB ? -1 : itemA > itemB ? 1 : 0;
        } else if (sortingMethod === "desc") {
          return itemA > itemB ? -1 : itemA < itemB ? 1 : 0;
        }
        return 0;
      });
    },

    // set search attribute
    setSearchAttribute(searchAttribute) {
      this.searchAttribute = searchAttribute;
    },
  },
};
</script>

<style scoped>
@import "./HomePage.module.css";
</style>
