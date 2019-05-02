<template>
  <div class="card">
    <div class="card-body">
      <b-loading :is-full-page="false" 
                 :active.sync="isLoading" />
      <div class="pdd-horizon-30 pdd-vertical-30">
        <div class="mrg-btm-30 text-center">
          <img :src="$app.logo"
               style="max-width: 200px;"
               class="img-responsive">
        </div>
        <reset-password-form v-if="confirmed" 
                             :email="email" 
                             :token="token" />
        <form v-else @submit.prevent="onSubmit">
          <p class="mrg-btm-15 font-size-13">Confirm your email address</p>
          <input v-model.trim="token" type="hidden">
          <div class="form-group">
            <input v-model.trim="$v.email.$model" 
                   :class="{ 'is-invalid': $v.email.$error }" 
                   type="email" 
                   class="form-control"
                   placeholder="Email Address">
            <div v-if="$v.email.$error" class="invalid-feedback">Enter a valid email address.</div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="mrg-top-20 pull-right">
                <button :disabled="$v.$invalid" class="button is-primary">Submit</button>
              </div>
            </div>
          </div>
          <b-message v-if="error" 
                     class="mrg-top-20" 
                     type="is-danger">
            {{ error }}
          </b-message>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { isEmpty } from "lodash";
import { mapActions } from "vuex";
import ViewPort from "@/mixins/viewport";
import { required, email } from "vuelidate/lib/validators";
import ResetPasswordForm from "@/components/auth/password/resetform";
export default {
  components: {
    ResetPasswordForm
  },
  mixins: [ViewPort],
  props: {
    token: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      isLoading: false,
      error: null,
      confirmed: false,
      email: this.$route.query.email
    };
  },
  validations: {
    email: {
      required,
      email
    },
    token: {
      required
    }
  },
  metaInfo: {
    title: "Password Reset"
  },
  created() {
    // If we have the email and token submit the request
    if (!isEmpty(this.token) && !isEmpty(this.email)) {
      this.onSubmit();
    }
  },
  methods: {
    ...mapActions("auth", ["resetConfirm"]),
    onSubmit() {
      if (this.$v.$invalid) {
        return false;
      }

      this.error = null;
      this.isLoading = true;

      this.resetConfirm({
        email: this.email,
        token: this.token
      })
        .then(result => (this.confirmed = true))
        .catch(response => (this.error = response.errors.join("<br />")))
        .then(() => {
          this.isLoading = false;
        });
    }
  }
};
</script>

<style>
</style>
