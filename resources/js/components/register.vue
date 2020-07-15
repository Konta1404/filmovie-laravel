<template>
    <div class="container mt-5">
        <div class="row justify-content-md-center">
            <div class="col-6">
                <div class="card card-default">
                    <div class="card-header">Register</div>
                    <div class="card-body">
                        <div v-if="validationError" class="alert alert-danger">
                            <p class="m-0">{{ validationError.error }}</p>
                        </div>
                        <form autocomplete="off" method="post" @submit.prevent="register">
                            <div class="form-group">
                                <label for="email">Name</label>
                                <input type="text" id="name" class="form-control" placeholder="username" v-model="form.name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="form.email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" class="form-control" v-model="form.password" required>
                            </div>
                            <div class="form-group">
                                <label for="passwordConfirm">Confirm password</label>
                                <input type="password" id="passwordConfirm" class="form-control" v-model="form.password_confirmation" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        created() {
            if (User.loggedIn()) {
                this.$router.push({ name: 'home'})
            }
        },
        data() {
            return {
                form:{
                    name: null,
                    email: null,
                    password: null,
                    password_confirmation: null
                },
                validationError: null
            }
        },
        methods: {
            register() {

                if (this.form.password !== this.form.password_confirmation) {
                    this.validationError = {
                        error: 'Password doesn\'t match'
                    }
                    return false;
                }
                this.validationError = null;
                Vue.axios.post('/api/auth/register', this.form)
                    .then(res => {
                        User.responseAfterLogin(res)
                        this.$router.push({name: 'home'})
                    })
                    .catch(error => this.validatioError = error.response.data)
            }
        }
    }
</script>
