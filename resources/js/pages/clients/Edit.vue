<template>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-gradient-primary text-white">
                <h3 class="card-title text-center">Editar Cliente</h3>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4"
                        :class="{ error: v$ }">
                        <label for="cpf" class="form-label">CPF:</label>
                        <input
                            @blur="v$.client.cpf.$touch"
                            v-model="client.cpf"
                            type="text"
                            v-mask="'###.###.###-##'"
                            class="form-control"
                            :class="{'is-invalid': v$.client.cpf.$error, 'is-valid': v$.client.cpf.$dirty && !v$.client.cpf.$invalid}"
                            required>

                        <div class="invalid-feedback" v-if="v$.client.cpf.$dirty && v$.client.cpf.$invalid">
                            <div class="input-errors" v-for="error of v$.client.cpf.$errors" :key="error.$uid">
                                <div class="error-msg">Campo obrigatório</div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-3">
                        <label for="name" class="form-label">Nome:</label>
                        <input v-model="client.name" type="text" class="form-control" id="name" name="name" required @blur="v$.client.name.$touch"  :class="{'is-invalid': v$.client.name.$error, 'is-valid': v$.client.name.$dirty && !v$.client.name.$invalid}" >

                        <div class="invalid-feedback" v-if="v$.client.name.$dirty && v$.client.name.$invalid">
                            <div class="input-errors" v-for="error of v$.client.name.$errors" :key="error.$uid">
                                <div class="error-msg">Campo obrigatório</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <label for="email" class="form-label">Email:</label>
                        <input @blur="v$.client.email.$touch"  :class="{'is-invalid': v$.client.email.$error, 'is-valid': v$.client.email.$dirty && !v$.client.email.$invalid}" v-model="client.email" type="text" class="form-control" id="email" name="email" required>

                        <div class="invalid-feedback" v-if="v$.client.email.$dirty && v$.client.email.$invalid">
                            <div class="input-errors" v-for="error of v$.client.email.$errors" :key="error.$uid">
                                <div class="error-msg">Campo obrigatório</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="birth_date" class="form-label">Data Nascimento:</label>
                        <input v-model="client.birth_date" type="date" class="form-control" id="birth_date" name="birth_date" required @blur="v$.client.birth_date.$touch"  :class="{'is-invalid': v$.client.birth_date.$error, 'is-valid': v$.client.birth_date.$dirty && !v$.client.birth_date.$invalid}">
                        <div class="invalid-feedback" v-if="v$.client.birth_date.$dirty && v$.client.birth_date.$invalid">
                            <div class="input-errors" v-for="error of v$.client.birth_date.$errors" :key="error.$uid">
                                <div class="error-msg">Campo obrigatório</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sexo:</label>
                        <div>
                            <input v-model="client.gender" type="radio" id="male" name="gender" value="M">
                            <label for="male">Masculino</label>

                            <input v-model="client.gender" type="radio" id="female" name="gender" value="F">
                            <label for="female">Feminino</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="address" class="form-label">Endereço:</label>
                        <input required @blur="v$.client.address.$touch" :class="{'is-invalid': v$.client.address.$error, 'is-valid': v$.client.address.$dirty && !v$.client.address.$invalid}" v-model="client.address" type="text" class="form-control" id="address" name="address">
                        <div class="invalid-feedback" v-if="v$.client.address.$dirty && v$.client.address.$invalid">
                            <div class="input-errors" v-for="error of v$.client.address.$errors" :key="error.$uid">
                                <div class="error-msg">Campo obrigatório</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="city" class="form-label">Cidade: <span v-if="client.city" class="badge bg-secondary">{{client.city.name}} - {{client.city.state.name}}</span></label>
                        <input type="text" v-model="citySearchString" @change="searchCity()" class="form-control" placeholder="Digite para buscar a cidade..."/>
                        <div class="list-group" v-if="citiesFounded.length > 0">
                            <a href="#" class="list-group-item list-group-item-action" v-for="(city, k) in citiesFounded" :key="k" @click="selectCity(city)">{{city.name}}</a>
                        </div>
                        <div class="invalid-feedback" v-if="v$.client.city.$dirty && v$.client.city.$invalid">
                            <div class="input-errors" v-for="error of v$.client.city.$errors" :key="error.$uid">
                                <div class="error-msg">Campo obrigatório</div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-2" >
                        <label for="city" class="form-label">Cidade / Estado </label>
                        <input disabled required @blur="v$.client.city.$touch" :class="{'is-invalid': v$.client.city.$error, 'is-valid': v$.client.city.$dirty && !v$.client.address.$invalid}" :value="(client.city) ? `${client.city.name} - ${client.city.state.name}` : ''" type="text" class="form-control" id="address" name="address">
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-start" >
                    <router-link :to="{ name: 'ListClient' }" class="btn btn-outline-secondary" style="margin-right: 1em;">
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
        client: { cpf: '', name: '', birth_date: '', gender: 'M', address: '', state: '', city: null, email: '', id: '' },
        citySearchString: '',
        citiesFounded: [],
        successMessage: ''
    }),
    mounted() {
        this.getItem()
    },
    validations () {
        return {
            client: {
                cpf: { required },
                name: { required },
                birth_date: { required },
                gender: { required },
                address: { required },
                state: { },
                city: { required },
                email: { required }
            }
        }
    },
    methods: {
        async getItem() {
            try {
                const response = await axios.get(`${apiRoutes.client.get}/${this.$route.params.id}`);
                this.client = response.data;
            }
            catch (error) {
                toast('Não encontramos o Cliente', {
                    autoClose: 4500,
                    position: toast.POSITION.BOTTOM_RIGHT,
                });
            }
        },
        async submitForm() {
            try {
                this.v$.client.$touch()
                if (!this.v$.client.$invalid) {

                    const data = this.client;
                    data.city_id = this.client.city.city_id;
                    data.id = this.$route.params.id;

                    await axios.put(`${apiRoutes.client.update}${this.$route.params.id}`, data);

                    toast("Alterado com sucesso!", {
                        autoClose: 2500,
                        position: toast.POSITION.BOTTOM_RIGHT,
                    });
                    setTimeout(() => {
                        this.$router.push({ name: 'ListClient' });
                    }, 1000);
                } else{
                    console.error('Validation errors:');
                    console.error( this.v$.client.$errors);
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
            this.client.city = city
            this.citiesFounded = []
            this.citySearchString = ''
        }
    }
}
</script>
