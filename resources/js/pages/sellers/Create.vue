<template>

    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-gradient-primary text-white">
                <h3 class="card-title text-center">Cadastrar Representante</h3>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="name" class="form-label">Nome:</label>
                        <input v-model="seller.name" type="text" class="form-control" id="name" name="name" required @blur="v$.seller.name.$touch"  :class="{'is-invalid': v$.seller.name.$error, 'is-valid': v$.seller.name.$dirty && !v$.seller.name.$invalid}" >

                        <div class="invalid-feedback" v-if="v$.seller.name.$dirty && v$.seller.name.$invalid">
                            <div class="input-errors" v-for="error of v$.seller.name.$errors" :key="error.$uid">
                                <div class="error-msg">Campo obrigatório</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="city" class="form-label">Cidade: <span v-if="seller.city" class="badge bg-secondary">{{seller.city.name}} - {{seller.city.state.name}}</span></label>
                        <input type="text" v-model="citySearchString" @change="searchCity()" class="form-control" placeholder="Digite para buscar a cidade..."/>
                        <div class="list-group" v-if="citiesFounded.length > 0">
                            <a href="#" class="list-group-item list-group-item-action" v-for="(city, k) in citiesFounded" :key="k" @click="selectCity(city)">{{city.name}}</a>
                        </div>
                        <div class="invalid-feedback" v-if="v$.seller.city.$dirty && v$.seller.city.$invalid">
                            <div class="input-errors" v-for="error of v$.seller.city.$errors" :key="error.$uid">
                                <div class="error-msg">Campo obrigatório</div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-2" >
                        <label for="city" class="form-label">Cidade / Estado </label>
                        <input disabled required @blur="v$.seller.city.$touch" :class="{'is-invalid': v$.seller.city.$error, 'is-valid': v$.seller.city.$dirty && !v$.seller.city.$invalid}" :value="(seller.city) ? `${seller.city.name} - ${seller.city.state.name}` : ''" type="text" class="form-control" id="city" name="city">
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-start" >
                    <router-link :to="{ name: 'ListSellers' }" class="btn btn-outline-secondary" style="margin-right: 1em;">
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
        seller: { name: '', state: '', city: null},
        citySearchString: '',
        citiesFounded: [],
        successMessage: ''
    }),
    validations () {
        return {
            seller: {
                name: { required },
                state: { },
                city: { required },
            }
        }
    },
    methods: {
        async submitForm() {
            try {
                this.v$.seller.$touch()
                if (!this.v$.seller.$invalid) {

                    const data = this.seller
                    data.city_id = this.seller.city.city_id;

                    await axios.post(apiRoutes.sellers.create, data);

                    toast("Cadastrado com sucesso !", {
                        autoClose: 2500,
                        position: toast.POSITION.BOTTOM_RIGHT,
                    });
                    setTimeout(() => {
                        this.$router.push({ name: 'ListSellers' });
                    }, 1000);
                } else{
                    console.error('Validation errors:');
                    console.error( this.v$.seller.$errors);
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
        selectCity(city) {
            this.seller.city = city
            this.citiesFounded = []
            this.citySearchString = ''
        }
    }
}
</script>
