<template>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 mt-5">
                <h1>Administration</h1>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-6">
                <div class="form-group">
                    <b-form-select id="exampleFormControlSelect1" v-model="selected" :options="options">
                        <template v-slot:first>
                            <b-form-select-option :value="null" disabled>-- Please select an option --</b-form-select-option>
                        </template>
                    </b-form-select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <category :selected-object="selected" v-if="selected === 'category'"></category>
                <actor :selected-object="selected" v-if="selected === 'actors'"></actor>
                <director :selected-object="selected" v-if="selected === 'directors'"></director>
                <movie :selected-object="selected" v-if="selected === 'movies'"></movie>
            </div>
        </div>
    </div>
</template>
<script>
    import Category from './category'
    import Movie from './movie'
    import Actor from './actor'
    import Director from './director'
    import DirectorModal from "./director-modal";
    import MovieModal from "./movie-modal";

    export default {
        created() {
            if (!User.loggedIn()) {
                this.$router.push({ name: 'login'})
            }
        },
        components: {
            Category,
            Actor,
            Director,
            Movie
        },
        data() {
            return {
                selected: null,
                options: [
                    { value: 'movies', text: 'Movies' },
                    { value: 'actors', text: 'Actors' },
                    { value: 'directors', text: 'Directors' },
                    { value: 'category', text: 'Category' },
                ]
            }
        },
    }
</script>
