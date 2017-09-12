<template>
  <form class="row" style="margin-top: 20px;" @submit.prevent="onLogin">
    <div class="col-md-12 col-md-offset-6" style="margin-bottom: 20px;">
      <h3>Prijava</h3>
    </div>
    <div class="col-md-12 col-md-offset-6" style="margin-bottom: 20px;">
      <at-input
        type="email"
        placeholder="Vnesi e-mail"
        :status="getStatus('email')"
        v-model.trim="login.email"
        icon="at-sign"></at-input>
    </div>

    <div class="col-md-12 col-md-offset-6" style="margin-bottom: 20px;">
      <at-input
        placeholder="Vnesi geslo"
        v-model.trim="login.password"
        :status="getStatus('password')"
        type="password"
        icon="lock"></at-input>
    </div>

    <div class="col-md-12 col-md-offset-6">
      <at-button
        type="submit"
        icon="icon-log-in">Prijavi se</at-button>
    </div>
  </form>
</template>

<script type="text/babel">
  import { mapState, mapActions, mapMutations } from 'vuex'

  export default {
    name: 'Auth',
    data () {
      return {
        login: {
          email: null,
          password: null
        }
      }
    },
    methods: {
      onLogin () {
        var self = this

        this.loginUser({
          data: {
            email: this.login.email,
            password: this.login.password
          }
        }).then((res) => {
          this.saveToken()
          self.$Notify.success('Prijava uspešna.')
          this.$router.push('/offers/')
        }).catch((e) => {
          try {
            if (self.error_user.response.data[0] === 'user_not_found') {
              self.$Message.error('Uporabnik ne obstaja ali pa so podatki napačni.')
            }

            if (typeof self.error_user.response.data['password'] !== 'undefined') {
              self.$Message.error(self.error_user.response.data['password'][0])
            }
            if (typeof self.error_user.response.data['email'] !== 'undefined') {
              self.$Message.error(self.error_user.response.data['email'][0])
            }
          } catch (ee) {
            console.log('2', ee)
          }
        })
      },
      getStatus (key) {
        if (this.error_user !== null) {
          if (typeof this.error_user.response.data[key] !== 'undefined') {
            return 'error'
          }
        }
        return ''
      },
      ...mapActions([
        'loginUser'
      ]),
      ...mapMutations([
        'saveToken'
      ])
    },
    computed: {
      ...mapState({
        user: state => state.user.user,
        pending_user: state => state.user.pending.user,
        error_user: state => state.user.error.user
      })
    }
  }
</script>
