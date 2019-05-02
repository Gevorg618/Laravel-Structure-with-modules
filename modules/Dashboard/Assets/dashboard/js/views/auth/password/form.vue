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
        <p class="mrg-btm-15 font-size-13">Please enter your email address in order to start the process of resetting your password.</p>
        <form @submit.prevent="onSubmit">
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
              <div class="mrg-top-20 pull-left">
                <router-link :to="{name: 'login.index'}" class="btn btn-default">Sign In?</router-link>
              </div>
              <div class="mrg-top-20 pull-right">
                <vue-recaptcha ref="captcha" 
                               :sitekey="$app.settings.captcha.key"
                               @verify="onVerify">
                  <button :disabled="this.$v.$invalid" 
                          class="button is-primary">Submit</button>
                </vue-recaptcha>
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
import { mapActions } from "vuex";
import ViewPort from "@/mixins/viewport";
import VueRecaptcha from "vue-recaptcha";
import { required, email } from "vuelidate/lib/validators";
export default {
  components: {
    VueRecaptcha
  },
  mixins: [ViewPort],
  data() {
    return {
      email: "",
      captcha: "",
      isLoading: false,
      error: null
    };
  },
  validations: {
    email: {
      required,
      email
    }
  },
  metaInfo: {
    title: "Password Reset"
  },
  methods: {
    ...mapActions("auth", ["reset"]),
    onVerify(response) {
      this.captcha = response;
      this.onSubmit();
    },
    onSubmit() {
      this.$refs.captcha.execute();

      if (this.$v.$invalid) {
        return false;
      }

      this.error = null;
      this.isLoading = true;

      this.reset({
        email: this.email,
        captcha: this.captcha
      })
        .then(result => {
          this.$toast.open({
            message: "Reset password instructions sent.",
            type: "is-success"
          });
          this.$router.push({ name: "login.index" });
        })
        .catch(response => {
          this.error = response.errors.join("<br />");
          this.$refs.captcha.reset();
        })
        .then(() => {
          this.isLoading = false;
        });
    }
  }
};
</script>
