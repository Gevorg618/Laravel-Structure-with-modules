<template>
  <b-table
    :data="data"
    :default-sort="sort"
    :striped="true"
    :narrowed="true"
    :hoverable="true"
    :loading="isLoading"
    :mobile-cards="true"
    :total="total"
    :per-page="perPage"
    :current-page="currentPage"
    paginated
    backend-sorting
    backend-pagination
    @sort="onSort"
    @page-change="onPageChange">

    <template slot-scope="props">

      <b-table-column
        :visible="false"
        field="id" 
        label="ID" 
        width="40" 
        sortable>
        {{ props.row.id }}
      </b-table-column>

      <b-table-column
        field="subject" 
        label="Subject"
        sortable>
        <span class="text-info">
          <router-link :to="{'name': 'tickets.view', params: {hash: props.row.hash}}">{{ props.row.subject }}</router-link>
        </span>
      </b-table-column>

      <b-table-column
        field="created_date" 
        label="Created At"
        sortable>
        {{ createdAt(props.row) }}
      </b-table-column>

      <b-table-column
        field="public_comments_count" 
        label="Comments"
        sortable>
        {{ props.row.public_comments_count | number }}
      </b-table-column>
    </template>

    <template slot="empty">
      <section class="section">
        <div class="content has-text-grey has-text-centered">
          <p>No Support Tickets Found Found.</p>
        </div>
      </section>
    </template>
    <template slot="bottom-left">
      <span v-if="total" class="has-text-grey">
        Displaying {{ from }} - {{ to }} out of {{ formatTotal }} results.
      </span>
    </template>
  </b-table>
</template>

<script>
import { get, includes } from "lodash";
import moment from "moment";
import accounting from "accounting";
export default {
  props: {
    data: {
      type: [Array, Object],
      required: true
    },
    total: {
      type: [Number, String],
      default: 0
    },
    currentPage: {
      type: [Number, String],
      default: 0
    },
    from: {
      type: [Number, String],
      default: 0
    },
    to: {
      type: [Number, String],
      default: 0
    },
    perPage: {
      type: [Number, String],
      default: 50
    },
    sort: {
      type: Array,
      default: () => ["created_date", "desc"]
    },
    isLoading: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {};
  },
  computed: {
    formatTotal: function() {
      return accounting.formatNumber(this.total);
    },
    formatFrom: function() {
      return accounting.formatNumber(this.from);
    },
    formatTo: function() {
      return accounting.formatNumber(this.to);
    }
  },
  methods: {
    createdAt(row) {
      let date = get(row, "created_date");
      if (date) {
        return moment(date).format("M/D/YYYY h:mm A");
      }
      return "--";
    },
    onSort(field, order) {
      this.$emit("sort-change", field, order);
    },
    onPageChange(page) {
      this.$emit("page-change", page);
    }
  }
};
</script>
