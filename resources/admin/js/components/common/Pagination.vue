<template>
  <div class="pagination" v-if="pagination">

    <span class="pagination__shown">Показано {{ shown }} из {{ pagination.total }}</span>

    <div class="pagination__links">

            <span class="pagination__links-button pagination__links-button-icon"
                  :class="{'pagination__links-button-link' : pagination.current_page !== 1}"
                  @click="setPage(1, pagination.per_page)"><IconBackwardFast/></span>

      <span class="pagination__links-button pagination__links-button-icon"
            :class="{'pagination__links-button-link' : pagination.current_page !== 1}"
            @click="setPage(pagination.current_page - 1, pagination.per_page)"><IconBackward/></span>

      <span class="pagination__links-spacer"><span v-if="hasBefore">...</span></span>

      <span class="pagination__links-button" v-for="page in pages"
            :class="{
                    'pagination__links-button-link-active': page === pagination.current_page && pagination.last_page > 1,
                    'pagination__links-button-link': pagination.last_page > 1,
                  }"
            :key="page"
            @click="setPage(page, pagination.per_page)"
      >{{ page }}</span>

      <span class="pagination__links-spacer"><span v-if="hasAfter">...</span></span>

      <span class="pagination__links-button pagination__links-button-icon"
            :class="{'pagination__links-button-link' : pagination.current_page !== pagination.last_page}"
            @click="setPage(pagination.current_page + 1, pagination.per_page)"><IconForward/></span>

      <span class="pagination__links-button pagination__links-button-icon"
            :class="{'pagination__links-button-link' : pagination.current_page !== pagination.last_page}"
            @click="setPage(pagination.last_page, pagination.per_page)"><IconForwardFast/></span>
    </div>

  </div>
</template>

<script>
import IconBackwardFast from "./Icons/IconBackwardFast.vue";
import IconBackward from "./Icons/IconBackward.vue";
import IconForward from "./Icons/IconForward.vue";
import IconForwardFast from "./Icons/IconForwardFast.vue";

export default {
  emits: ['pagination'],

  props: {
    pagination: {
      type: Object,
      default: () => ({
        current_page: 1,
        last_page: 1,
        from: 1,
        to: 1,
        total: 1,
        per_page: 10,
      })
    },
    options: {type: Array, default: () => ([5, 10, 25, 50, 100])},
  },

  components: {
    IconBackward,
    IconBackwardFast,
    IconForward,
    IconForwardFast,
  },

  data: () => ({
    max_links: 7,
  }),

  computed: {
    shown() {
      return this.pagination.from && this.pagination.to ? `${this.pagination.from} - ${this.pagination.to}` : '0'
    },
    pages() {
      let pages = [];
      if (this.pagination.last_page <= this.max_links) {
        for (let i = 1; i <= this.pagination.last_page; i++) {
          pages.push(i);
        }
      } else {
        let start = this.pagination.current_page - Math.floor(this.max_links / 2);
        if (start < 1) {
          start = 1;
        }
        if (start + this.max_links > this.pagination.last_page) {
          start = this.pagination.last_page - this.max_links + 1;
        }
        for (let i = start; i < start + this.max_links; i++) {
          pages.push(i);
        }
      }
      return pages;
    },

    hasBefore() {
      return (this.pagination.last_page > this.max_links) && (this.pagination.current_page - Math.floor(this.max_links / 2) > 1);
    },

    hasAfter() {
      return (this.pagination.last_page > this.max_links) && (this.pagination.current_page - Math.floor(this.max_links / 2) + this.max_links - 1 < this.pagination.last_page);
    },

    perPage: {
      get() {
        return this.pagination.per_page;
      },
      set(value) {
        if (this.pagination.per_page !== value) {
          this.setPage(1, value);
        }
      }
    },
  },

  methods: {
    setPage(page, perPage) {
      if (page < 1 || page > this.pagination.last_page || (page === this.pagination.current_page && perPage === this.pagination.per_page)) {
        return;
      }

      this.$emit('pagination', page, perPage);
    }
  }
}
</script>

<style lang="scss">
@use "sass:math";

$project_font: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji !default;
$animation_time: 150ms !default;
$animation: cubic-bezier(0.24, 0.19, 0.28, 1.29) !default;
$base_size_unit: 30px !default;
$base_white_color: #ffffff !default;
$base_primary_color: #0D74D7 !default;
$base_primary_hover_color: lighten(#0D74D7, 10%) !default;
$base_black_color: #1e1e1e !default;
$base_gray_color: #8f8f8f !default;
$base_light_gray_color: #e5e5e5 !default;
$base_lightest_gray_color: #f7f7f7 !default;

.pagination {
  display: flex;
  flex-direction: row;
  height: $base_size_unit;
  margin: 20px 20px 20px 20px;

  &__shown, &__per-page {
    flex-grow: 0;
    flex-shrink: 0;
    font-family: $project_font;
    line-height: $base_size_unit;
    font-size: 14px;
    padding-right: 15px;
  }

  &__per-page {
    display: flex;
    flex-direction: row;

    &-select {
      margin-right: 10px;
    }

    &-text {

    }
  }

  &__links {
    flex-grow: 1;
    display: flex;
    justify-content: right;
    line-height: $base_size_unit;
    font-family: $project_font;

    &-spacer {
      width: math.div($base_size_unit, 2);
      height: $base_size_unit;
      line-height: $base_size_unit;
      text-align: center;
      cursor: default;
      color: $base_gray_color;
      margin-right: 5px;
    }

    &-button {
      width: $base_size_unit;
      height: $base_size_unit;
      line-height: $base_size_unit;
      text-align: center;
      cursor: default;
      border-radius: 4px;
      box-sizing: border-box;
      color: $base_gray_color;
      border: 1px solid transparent;
      background-color: transparent;

      &:not(:last-child) {
        margin-right: 5px;
      }

      &-link {
        cursor: pointer;
        color: $base_black_color;
        border: 1px solid $base_light_gray_color;
        background-color: $base_lightest_gray_color;
        transition: border-color $animation $animation_time;

        &:not(&-active):hover {
          border-color: $base_primary_hover_color;
        }

        &-active {
          color: $base_white_color !important;
          border-color: $base_primary_color !important;
          background-color: $base_primary_color !important;
          cursor: default;
        }
      }

      &-icon {
        display: flex;
        justify-content: center;
        align-items: center;

        & > svg {
          width: math.div($base_size_unit, 2);
          height: math.div($base_size_unit, 2);
        }
      }
    }
  }
}
</style>
