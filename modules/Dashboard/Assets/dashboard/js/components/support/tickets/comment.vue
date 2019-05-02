<template>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div :class="{'bg-primary': isStaff, 'bg-info': !isStaff}" class="card-header text-white">
          {{ title }}
        </div>
        <div class="card-body">
          <div v-html="comment.content"/>
        </div>
        <div class="card-footer">{{ comment.created_date | date }}</div>
      </div>
    </div>
  </div>
</template>

<script>
import { get } from "lodash";
export default {
  props: {
    comment: {
      type: Object,
      required: true
    },
    ticket: {
      type: Object,
      required: true
    }
  },
  computed: {
    isStaff: function() {
      return get(this.comment, "user.user_type") == 1;
    },
    title: function() {
      return this.isStaff ? "Staff" : "You";
    }
  }
};
</script>
