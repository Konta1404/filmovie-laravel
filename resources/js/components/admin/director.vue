<template>
    <b-container>
        <b-table
            v-if="directors"
            show-empty
            small
            stacked="md"
            :items="directors"
            :fields="fields"
            @click="v-b-modal.edit-modal"
        >

            <template v-slot:cell(actions)="row">
                <b-button v-b-modal.edit-actor-modal v-on:click="saveId(row.item)" variant="info" edit-modal>Edit</b-button>
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
            id="edit-actor-modal"
            ref="modal"
            title="Edit Actor"
            @show="showModal"
            @hidden="resetModal"
            @ok="handleOk"
        >
            <form ref="form" @submit.stop.prevent="handleSubmit">
                <b-form-group
                    :state="states.firstNameState"
                    label="First name"
                    label-for="first-name-input"
                    invalid-feedback="First name is required"
                >
                    <b-form-input
                        id="first-name-input"
                        v-model="form.first_name"
                        :state="states.firstNameState"
                        required
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    :state="states.lastNameState"
                    label="Last name"
                    label-for="last-name-input"
                >
                    <b-form-input
                        id="last-name-input"
                        v-model="form.last_name"
                        :state="states.lastNameState"
                    ></b-form-input>
                </b-form-group>
                <b-form-group>
                    <label>Date of birth</label>
                    <b-form-datepicker v-model="form.date_of_birth"></b-form-datepicker>
                </b-form-group>
                <b-form-group
                    :state="states.biographyState"
                    label="Biography"
                    label-for="biography-input"
                >
                    <b-form-textarea
                        id="name-input"
                        v-model="form.biography"
                        rows="5"
                        :state="states.biographyState"
                    ></b-form-textarea>
                </b-form-group>
                <b-form-group
                    :state="states.photo"
                    label="Photo url"
                    label-for="photo-input"
                    invalid-feedback="Name is required"
                >
                    <b-form-input
                        id="photo-input"
                        v-model="form.photo"
                        :state="states.photo"
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
                directors: [],
                fields: ['id', 'first_name', 'last_name' , 'date_of_birth', 'created_at', 'actions'],
                name: '',
                directorItem: {},
                states: {
                    firstNameState: null,
                    lastNameState: null,
                    dateOfBirthState: null,
                    biographyState: null,
                    photoState: null,
                },
                form: {
                    first_name: '',
                    last_name: '',
                    date_of_birth: '',
                    biography: '',
                    photo: ''
                }
            }
        },
        mounted() {
            this.getDirectors();
        },
        methods: {
            saveId(item) {
                this.directorItem = item;
            },
            getDirectors() {
                Vue.axios.get('/api/directors')
                    .then(response => {
                        this.directors = response.data;

                        for (let i = 0; i < this.directors.length; i++) {
                            this.directors[i].created_at = moment(this.directors[i].created_at).format('YYYY-MM-DD');
                            this.directors[i].updated_at = moment(this.directors[i].updated_at).format('YYYY-MM-DD');
                        }
                    });
            },
            showModal(){
                this.resetModal();
                this.form = this.directorItem;
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
                bvModalEvt.preventDefault()
                this.handleSubmit()
            },
            handleSubmit() {
                // Exit when the form isn't valid
                if (!this.checkFormValidity()) {
                    return
                }
                console.log(this.name);
                Vue.axios.put('/api/directors/' + this.directorItem.id, this.form)
                    .then(res => {
                        new Noty({
                            text: 'Category successfully updated', type: 'success',
                            layout: 'topRight',
                            timeout: 1000
                        }).show();
                        this.getDirectors();
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
                    this.$bvModal.hide('edit-actor-modal')
                })
            },
            deleteCategory(item) {
                Vue.axios.delete('/api/directors/' + item.id)
                    .then(res => {
                        new Noty({
                            text: 'Category successfully added', type: 'success',
                            layout: 'topRight',
                            timeout: 1000
                        }).show();
                        this.getDirectors();
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
