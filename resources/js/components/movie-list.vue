<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-6">
                <form @submit.stop.prevent="getMovies(query)">
                    <b-form-row>
                        <b-col>
                            <b-form-input v-model="query" type="text" debounce="500"></b-form-input>
                        </b-col>
                        <b-col>
                            <b-button v-on:click="getMovies(query)">Submit</b-button>
                        </b-col>
                    </b-form-row>
                </form>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-4 mb-4" v-for="movie in movies.data">
                <div v-on:click="goToSingleMovie(movie.id)" class="card mb-4 box-shadow">
                    <img v-if="movie.poster" v-bind:src="movie.poster" class="card-img-top" v-bind:alt="movie.name" style="width: 100%; height: auto; max-height: 350px; display: block;"  data-holder-rendered="true">
                    <img v-if="!movie.poster" src="https://via.placeholder.com/348x350" alt="">
                    <div class="card-body">
                        <span>{{ movie.name }}</span>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">{{ movie.year }}</small>
                        </div>
                        <p v-if="movie && movie.description && movie.description.length < 50" class="card-text">{{ movie.description }}</p>
                        <p v-if="movie && movie.description && movie.description.length > 50" class="card-text">{{ movie.description.substring(0,50) + '..' }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <pagination :data="movies" @pagination-change-page="getMovies"></pagination>
            </div>
        </div>
    </div>
</template>
<style>
    .card {
        cursor: pointer;
        height: 100%;
    }
</style>
<script>
    export default {
        data() {
            return {
                movies: {},
                query: ''
            };
        },
        mounted() {
            this.getMovies();
        },
        methods: {
            goToSingleMovie(id){
                this.$router.push({ name: 'singleMovie', params: {id: id} })
            },
            getMovies(page = 1) {
                const pathQuery = `/movies?page=${page}&query=${this.query}`;
                let searchObject = {};
                if (this.$route.fullPath !== pathQuery) {
                    if (!this.$route.fullPath.includes(`query=${this.query}`)) {
                        searchObject.page = 1;
                        searchObject.query = this.query;
                    } else if (!this.$route.fullPath.includes(`page=${page}`)){
                        searchObject.page = page;
                        searchObject.query = this.query;
                    }
                    this.$router.push({query: searchObject});
                } else {
                    searchObject.page = page;
                }

                Vue.axios.post('http://filmovie.test/api/movies/search?page=' + searchObject.page, { query: this.query})
                    .then(response => {
                        this.movies = response.data;
                    });
            },
        }
    }
</script>
