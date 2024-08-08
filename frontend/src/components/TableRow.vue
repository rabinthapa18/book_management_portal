<template>
  <tr :class="{ selected: isSelected }">
    <td>
      <input type="checkbox" :checked="isSelected" @change="toggleSelection" />
    </td>
    <td>{{ bookIndex }}</td>
    <td>{{ book.title }}</td>
    <td>{{ book.genre.join(", ") }}</td>
    <td>{{ book.author }}</td>
  </tr>
</template>

<script>
export default {
  props: {
    book: Object,
    isSelected: Boolean,
  },
  computed: {
    bookIndex() {
      return this.$parent.books.indexOf(this.book) + 1;
    },
  },
  methods: {
    toggleSelection(event) {
      const bookId = this.book.id;
      // emit event to parent component
      this.$emit("update-selection", bookId, event.target.checked);
    },
  },
};
</script>

<style scoped>
@import "./TableRow.module.css";
</style>
