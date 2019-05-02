<template>
  <div class="row">
    <div class="col-md-12">
      <div class="page-title">
        <h4>{{ ticket.subject }} <small>#{{ id }}</small></h4>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="pdd-horizon-30 pdd-vertical-30">
            <div v-html="content"/>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="page-title">
        <h5>Comments ({{ comments.length }})</h5>
      </div>
    </div>
    <div class="col-md-12">
      <ticket-comment v-for="comment in comments" 
                      :key="comment.id" 
                      :comment="comment"
                      :ticket="ticket" />
    </div>
  </div>
</template>

<script>
import { get } from "lodash";
import { mapActions } from "vuex";
import accounting from "accounting";
import TicketComment from "@/components/support/tickets/comment";
export default {
  components: {
    TicketComment
  },
  props: {
    hash: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      ticket: {}
    };
  },
  computed: {
    content: function() {
      let text = get(this.ticket, "content_text.content");
      let html = get(this.ticket, "content_html.content");
      if (text) {
        return text;
      } else if (html) {
        return html;
      }

      return "N/A";
    },
    id: function() {
      return accounting.formatNumber(this.ticket.id);
    },
    comments: function() {
      return get(this.ticket, "public_comments", []);
    }
  },
  metaInfo: {
    title: "Viewing Support Ticket"
  },
  created() {
    this.get(this.hash)
      .then(response => (this.ticket = response))
      .catch(error => this.$router.push({ name: "errors.404" }));
  },
  methods: {
    ...mapActions("tickets", ["get"])
  }
};
</script>

<style>
</style>
