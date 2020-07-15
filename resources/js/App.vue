<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="#">Navbar</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <router-link to="/" class="nav-item nav-link">Home</router-link>
                        <router-link to="/movies" class="nav-item nav-link">Movies</router-link>
                        <router-link v-if="!isLogged" to="/login" class="nav-item nav-link">Login</router-link>
                        <router-link v-if="!isLogged" to="/register" class="nav-item nav-link">Register</router-link>
                        <router-link v-if="isLogged" to="/admin" class="nav-item nav-link">Administracija</router-link>
                    </ul>
                    <form class="form-inline my-2 my-lg-0">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </nav>
        <div id="content">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
    import User from "./Helpers/User";

    export default {
        data() {
            return {
                isLogged: User.hasToken()
            }
        },
    }
</script>
