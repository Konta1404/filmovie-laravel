<template>
    <b-container>
        <!-- User Interface controls -->
        <b-table
            v-if="movies"
            show-empty
            small
            stacked="md"
            :items="categories"
            :fields="fields"
            @click="v-b-modal.edit-modal"
        >

            <template v-slot:cell(actions)="row">
                <b-button v-b-modal.edit-modal v-on:click="saveId(row.item)" variant="info" edit-modal>Edit</b-button>
                <b-button variant="danger" v-on:click="deleteCategory(row.item)">Delete</b-button>
            </template>

            <template v-slot:row-details="row">
                <b-card>
                    <ul>
                        <li v-for="(value, key) in row.item" :key="key">{{ key }}: {{ value }}</li>
                    </ul>
                </b-card>
            </template>
        </b-table>
        <b-modal
            id="edit-modal"
            ref="modal"
            title="Edit Category"
            @show="showModal"
            @hidden="resetModal"
            @ok="handleOk"
        >
            <form ref="form" @submit.stop.prevent="handleSubmit">
                <b-form-group
                    label="Name"
                    label-for="name-input"
                    invalid-feedback="Name is required"
                >
                    <b-form-input
                        id="name-input"
                        v-model="name"
                        required
                    ></b-form-input>
                </b-form-group>
            </form>
        </b-modal>
    </b-container>
</template>

<script>
    import Noty from "noty";
    import moment from 'moment';
    export default {
        data() {
            return {
                categories: [],
                movies: [],
                fields: ['id', 'name', 'created_at' , 'updated_at', 'actions'],
                name: '',
                categoryItem: {}
            }
        },
        mounted() {
            this.getMovies();
            this.getCategories();
        },
        methods: {
            saveId(item) {
                this.categoryItem = item;
            },
            getMovies() {
                Vue.axios.get('/api/movies')
                    .then(response => {
                        this.movies = response.data;

                        for (let i = 0; i < this.movies.length; i++) {
                            this.movies[i].created_at = moment(this.movies[i].created_at).format('YYYY-MM-DD');
                            this.movies[i].updated_at = moment(this.movies[i].updated_at).format('YYYY-MM-DD');
                        }
                    });
            },
            getCategories() {
                Vue.axios.get('/api/categories')
                    .then(response => {
                        this.categories = response.data;

                        for (let i = 0; i < this.categories.length; i++) {
                            this.categories[i].created_at = moment(this.categories[i].created_at).format('YYYY-MM-DD');
                            this.categories[i].updated_at = moment(this.categories[i].updated_at).format('YYYY-MM-DD');
                        }

                        console.log(this.categories);
                    });
            },
            showModal(){
                this.resetModal();
                this.name = this.categoryItem.name;
            },
            checkFormValidity() {
                const valid = this.$refs.form.checkValidity()
                this.nameState = valid
                return valid
            },
            resetModal() {
                this.name = ''
                this.nameState = null
            },
            handleOk(bvModalEvt) {
                console.log('asdasdasd');
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
                console.log(this.name);
                Vue.axios.put('/api/categories/' + this.categoryItem.id, {name: this.name})
                    .then(res => {
                        new Noty({
                            text: 'Category successfully updated', type: 'success',
                            layout: 'topRight',
                            timeout: 1000
                        }).show();
                        this.getCategories();
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
            },
            deleteCategory(item) {
                Vue.axios.delete('/api/categories/' + item.id)
                    .then(res => {
                        new Noty({
                            text: 'Category successfully added', type: 'success',
                            layout: 'topRight',
                            timeout: 1000
                        }).show();
                        this.getCategories();
                    })
                    .catch(error => {
                        new Noty({
                            text: error.message, type: 'alert',
                            layout: 'topRight',
                            timeout: 1000
                        }).show();
                    })
            }
        }
    }
</script>
