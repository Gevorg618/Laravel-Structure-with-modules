<template>
  <div class="card">
    <div class="card-body">
      <b-loading :is-full-page="false" 
                 :active.sync="isLoading" />
      <div class="pdd-horizon-30 pdd-vertical-30">
        <div class="mrg-btm-30 text-center">
          <div class="row">
            <div class="col-md-8 offset-md-2 col-sm-10 offset-sm-1">
              <img :src="$app.logo"
                   class="img-responsive">
            </div>
          </div>
        </div>
        <p class="mrg-btm-15 font-size-13">Please enter your email address and password to login</p>
        <form @submit.prevent="onSubmit">
          <div class="form-group">
            <input v-model.trim="$v.email.$model" 
                   :class="{ 'is-invalid': $v.email.$error }" 
                   type="email" 
                   class="form-control"
                   placeholder="Email Address">
            <div v-if="$v.email.$error" class="invalid-feedback">Enter a valid email address.</div>
          </div>
          <div class="form-group">
            <input v-model="$v.password.$model" 
                   :class="{ 'is-invalid': $v.password.$error }" 
                   type="password"
                   class="form-control" 
                   placeholder="Password">
            <div v-if="$v.password.$error" class="invalid-feedback">Enter a valid password.</div>
          </div>
          <div class="checkbox font-size-13 inline-block no-mrg-vertical no-pdd-vertical">
            <input id="rememberme" 
                   v-model="rememberme"
                   name="rememberme" 
                   type="checkbox">
            <label for="rememberme">Keep Me Signed In</label>
          </div>
          <div class="pull-right">
            <router-link :to="{name: 'login.reset'}">Forgot Password?</router-link>
          </div>
          <div class="mrg-top-30 text-right">
            <vue-recaptcha ref="captcha" 
                           :sitekey="$app.settings.captcha.key"
                           @verify="onVerify">
              <button :disabled="this.$v.$invalid" 
                      class="button is-primary">Login</button>
            </vue-recaptcha>
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
import ViewPort from "@/mixins/viewport";
import { required, email, minLength } from "vuelidate/lib/validators";
import VueRecaptcha from "vue-recaptcha";
import { mapActions } from "vuex";
export default {
  components: {
    VueRecaptcha
  },
  mixins: [ViewPort],
  data() {
    return {
      email: "",
      password: "",
      captcha: "",
      rememberme: false,
      isLoading: false,
      error: null
    };
  },
  validations: {
    email: {
      required,
      email
    },
    password: {
      required,
      minLength: minLength(4)
    }
  },
  metaInfo: {
    title: "Login"
  },
  methods: {
    ...mapActions("auth", ["login"]),
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

      this.login({
        email: this.email,
        password: this.password,
        captcha: this.captcha,
        remember: this.rememberme
      })
        .then(result => {
          this.$toast.open({
            message: "Login Successful.",
            type: "is-success"
          });
          this.$router.push({ name: "index" });
        })
        .catch(response => (this.error = response.errors.join("<br />")))
        .then(() => {
          this.isLoading = false;
          this.$refs.captcha.reset();
        });
    }
  }
};
</script>
