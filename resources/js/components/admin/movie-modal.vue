<template>
    <div class="col-md-6">
        <b-button v-b-modal.modal-prevent-closing>{{ this.$props.title }}</b-button>
        <b-modal
            id="modal-prevent-closing"
            ref="modal"
            v-bind:title="this.$props.title"
            @show="resetModal"
            @hidden="resetModal"
            @ok="handleOk"
        >
            <form ref="form" @submit.stop.prevent="handleSubmit">
                <b-form-group
                    :state="states.nameState"
                    label="Name"
                    label-for="first-name-input"
                    invalid-feedback="First name is required"
                >
                    <b-form-input
                        id="first-name-input"
                        v-model="form.name"
                        :state="states.nameState"
                        required
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    :state="states.yearState"
                    label="Year"
                    label-for="year-input"
                    invalid-feedback="Year must be whole number"
                >
                    <b-form-input
                        id="year-input"
                        type="number"
                        step="1"
                        v-model="form.year"
                        :state="states.yearState"
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    :state="states.ratingState"
                    label="Rating"
                    label-for="rating-input"
                    invalid-feedback="Rating must be between 1 and 10"
                >
                    <b-form-input
                        id="name-input"
                        type="number"
                        min="1"
                        max="10"
                        v-model="form.rating"
                        :state="states.ratingState"
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    :state="states.description"
                    label="Description"
                    label-for="description-input"
                >
                    <b-form-textarea
                        id="description-input"
                        v-model="form.description"
                        :state="states.description"
                    ></b-form-textarea>
                </b-form-group>
                <b-form-group
                    :state="states.poster"
                    label="Photo url"
                    label-for="poster-input"
                >
                    <b-form-input
                        id="poster-input"
                        v-model="form.poster"
                        :state="states.poster"
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    :state="states.sliderImageState"
                    label="Slider image url"
                    label-for="slider_image-input"
                >
                    <b-form-input
                        id="slider_image-input"
                        v-model="form.slider_image"
                        :state="states.sliderImageState"
                    ></b-form-input>
                </b-form-group>
                <label>Select directors</label>
                <tags-input element-id="tags"
                            v-model="formDirectors"
                            :existing-tags="directors"
                            :only-existing-tags="true"
                            :typeahead="true"></tags-input>
                <label>Select actors</label>
                <tags-input element-id="tags"
                            v-model="formActors"
                            :existing-tags="actors"
                            :only-existing-tags="true"
                            :typeahead="true"></tags-input>
                <label>Select categories</label>
                <tags-input element-id="tags"
                            v-model="formCategories"
                            :existing-tags="categories"
                            :only-existing-tags="true"
                            :typeahead="true"></tags-input>
            </form>
        </b-modal>
    </div>
</template>
<script>
    import Noty from 'noty';
    import director from "./director";

    export default {
        props: {
            title: String
        },
        data(){
            return {
                states: {
                    nameState: null,
                    lastNameState: null,
                    yearState: null,
                    ratingState: null,
                    posterState: null,
                    sliderImageState: null,
                },
                form: {
                    name: '',
                    year: '',
                    rating: 1,
                    poster: '',
                    slider_image: ''
                },
                formDirectors: [],
                formActors: [],
                formCategories: [],
                directors: [],
                actors: [],
                categories: []
            }
        },
        computed: {
            availableDirectors() {
                return this.directors.filter(opt => this.value.indexOf(opt) === -1)
            }
        },
        mounted() {
            this.getItemsForMovies()
        },
        methods: {
            checkFormValidity() {
                const valid = this.$refs.form.checkValidity()
                this.states.nameState = valid

                if(this.form.rating < 1 || this.form.rating > 10) {
                    this.states.ratingState = false;
                    return false;
                } else {
                    this.states.ratingState = null;
                }

                if(this.form.year % 1 !== 0) {
                    this.states.yearState = false;
                    return false;
                } else {
                    this.states.yearState = null;
                }

                return valid
            },
            getItemsForMovies(){
                Vue.axios.all([
                    Vue.axios.get('/api/directors'),
                    Vue.axios.get('/api/actors'),
                    Vue.axios.get('/api/categories'),
                ]).then(Vue.axios.spread((directors, actors, categories) => {
                    this.directors = directors.data;
                    this.directors.forEach(element=>{
                        element.key = element.id;
                        element.value = element.first_name;
                        if (element.last_name !== null) {
                            element.value += ' ' + element.last_name;
                        }
                    })
                    console.log(this.directors);
                    this.actors = actors.data;
                    this.actors.forEach(element=>{
                        element.key = element.id;
                        element.value = element.first_name;
                        if (element.last_name !== null) {
                            element.value += ' ' + element.last_name;
                        }
                    })
                    this.categories = categories.data;
                    this.categories.forEach(element=>{
                        element.key = element.id;
                        element.value = element.name;
                    })
                }));
            },
            resetModal() {
                this.form = {
                    name: '',
                    year: '',
                    rating: 1,
                    poster: '',
                    slider_image: ''
                };
            },
            handleOk(bvModalEvt) {
                // Prevent modal from closing
                bvModalEvt.preventDefault()
                // Trigger submit handler
                this.handleSubmit()
            },
            handleSubmit() {
                // Exit when the form isn't valid
                if (!this.checkFormValidity()) {
                    return
                }

                this.form.actors = this.actors;
                this.form.directos = this.directors;
                this.form.categories = this.categories;

                Vue.axios.post('/api/movies', this.form)
                    .then(res => {
                        new Noty({
                            text: 'Movie successfully added', type: 'success',
                            layout: 'topRight',
                            timeout: 1000
                        }).show();
                    })
                    .catch(error => {
                        new Noty({
                            text: error.message, type: 'alert',
                            layout: 'topRight',
                            timeout: 1000
                        }).show();
                    })

                // Hide the modal manually
                this.$nextTick(() => {
                    this.$bvModal.hide('modal-prevent-closing')
                })
            }
        }
    }
</script>
