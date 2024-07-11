<template>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-gradient-primary text-white">
                <h3 class="card-title text-center">Editar Cidade</h3>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="name" class="form-label">Nome:</label>
                        <input v-model="city.name" type="text" class="form-control" id="name" name="name" required @blur="v$.city.name.$touch"  :class="{'is-invalid': v$.city.name.$error, 'is-valid': v$.city.name.$dirty && !v$.city.name.$invalid}" >

                        <div class="invalid-feedback" v-if="v$.city.name.$dirty && v$.city.name.$invalid">
                            <div class="input-errors" v-for="error of v$.city.name.$errors" :key="error.$uid">
                                <div class="error-msg">Campo obrigatório</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="country" class="form-label">Pais: <span v-if="city.country" class="badge bg-secondary">{{city.country.name}}</span></label>
                        <input type="text" v-model="countrySearchString" @change="searchCountries()" :class="{'is-invalid': invalidValidation, 'is-valid': !invalidValidation}" class="form-control" placeholder="Buscar um País..."/>
                        <div class="list-group" v-if="countryFounded.data.length > 0">
                            <a href="#" class="list-group-item list-group-item-action" v-for="(country, k) in countryFounded.data" :key="k" @click="selectCountry(country)">{{country.name}}</a>
                        </div>
                        <div class="invalid-feedback" v-if="invalidValidation">
                            <div class="input-errors">
                                <div class="error-msg">Campo obrigatório</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="country" class="form-label">Estado: <span v-if="city.country" class="badge bg-secondary">{{city.state.name}}</span></label>
                        <input type="text" :disabled="!city.country" v-model="stateSearchString" @change="searchStates()" class="form-control" placeholder="Buscar um estado..." :class="{'is-invalid': invalidValidation, 'is-valid': !invalidValidation}"/>
                        <div class="list-group" v-if="stateFounded.data.length > 0">
                            <a href="#" class="list-group-item list-group-item-action" v-for="(state, k) in stateFounded.data" :key="k" @click="selectState(state)">{{state.name}}</a>
                        </div>
                        <div class="invalid-feedback" v-if="invalidValidation">
                            <div class="input-errors" >
                                <div class="error-msg">Campo obrigatório</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-start" >
                    <router-link :to="{ name: 'ListCity' }" class="btn btn-outline-secondary" style="margin-right: 1em;">
                        <i class="bi bi-arrow-left-circle"></i> Voltar
                    </router-link>
                    <button @click="submitForm" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #6610f2);
}
</style>

<script>
import apiRoutes from '../../api'
import { useVuelidate } from '@vuelidate/core'
import { required, email } from '@vuelidate/validators'
import { toast } from 'vue3-toastify';

export default {
    setup: () => ({ v$: useVuelidate() }),
    data: () => ({
        city: { name: '', state: '', country: '', id: '' },
        countrySearchString: '',
        stateSearchString: '',
        citySearchString: '',
        countryFounded: {data: []},
        stateFounded: {data: []},
        citiesFounded: [],
        invalidValidation: false,
    }),
    mounted() {
        this.getItem()
    },
    validations () {
        return {
            city: {
                name: { required },
            }
        }
    },
    methods: {
        async getItem() {
            try {
                const response = await axios.get(`${apiRoutes.cities.get}/${this.$route.params.id}`);
                this.city = response.data;
                this.city.country = response.data.state.country
                this.city.state = response.data.state
            }
            catch (error) {
                toast('Não encontramos a cidade', {
                    autoClose: 4500,
                    position: toast.POSITION.BOTTOM_RIGHT,
                });
            }
        },
        async submitForm() {
            try {
                this.v$.city.$touch()
                if (!this.v$.city.$invalid) {
                    if(this.city.country == '' || this.city.state == '') {
                        this.invalidValidation = true
                        toast('Selecione um país e um estado', {
                            autoClose: 4500,
                            position: toast.POSITION.BOTTOM_RIGHT,
                        });
                        return;
                    }
                    let data = this.city;
                    data.state_id = this.city.state.id
                    data.id = this.$route.params.id;

                    await axios.put(`${apiRoutes.cities.update}${this.$route.params.id}`, data);

                    toast("Alterado com sucesso!", {
                        autoClose: 2500,
                        position: toast.POSITION.BOTTOM_RIGHT,
                    });
                    setTimeout(() => {
                        this.$router.push({ name: 'ListCity' });
                    }, 1000);
                } else{
                    toast('Verifique os campos.', {
                        autoClose: 2500,
                        position: toast.POSITION.BOTTOM_RIGHT,
                    });
                }
            } catch (error) {
                if(error.response.status == 422) {
                    Object.entries(error.response.data.errors).forEach(([key, messages]) => {
                        messages.forEach(message => {
                            toast(message, {
                                autoClose: 4500,
                                position: toast.POSITION.BOTTOM_RIGHT,
                             });
                        });
                     })

                }
            }
        },
        async searchCity() {
            if(this.citySearchString.lenght < 3){ return }
            try {
                const response = await axios.get(apiRoutes.cities.get, {
                params:
                    { name: this.citySearchString }
                });
                this.citiesFounded = response.data

            } catch (error) {
                console.error('Error :', error);
                // Handle the error appropriately
            }
        },
        async searchCountries () {
            if(this.countrySearchString.lenght < 3){ return }
            try {
                const response = await axios.get(apiRoutes.countries.get, {
                params:
                    { name: this.countrySearchString }
                });
                this.countryFounded = response.data

            } catch (error) {
                console.error('Error :', error);
                // Handle the error appropriately
            }
        },
        async searchStates () {
            if(this.stateSearchString.lenght < 3){ return }
            try {
                const response = await axios.get(apiRoutes.states.get, {
                params:
                    { name: this.stateSearchString, country_id: this.city.country.id }
                });
                this.stateFounded = response.data

            } catch (error) {
                console.error('Error :', error);
                // Handle the error appropriately
            }
        },
        selectCity(city) {
            this.city.city = city
            this.citiesFounded = []
            this.citySearchString = ''
        },
        selectCountry(country) {
            this.city.country = country
            this.city.state = ''
            this.countryFounded = {data: []}
            this.countrySearchString = ''
        },
        selectState(state) {
            this.city.state = state
            this.stateFounded = {data: []}
            this.stateSearchString = ''
        }
    }
}
</script>
