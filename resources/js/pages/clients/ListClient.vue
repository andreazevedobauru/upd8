<template>
    <div class="container mt-5">
        <h1 class="mb-4">Clientes</h1>
        <div v-if="successMessage" class="alert alert-success">
            {{ successMessage }}
        </div>
        <div class="d-flex justify-content-end mb-4">
            <router-link :to="{ name: 'CreateClients' }" class="btn btn-success" style="margin-right: 1em;"><i class="bi bi-plus-lg"></i> Cadastrar novo Cliente</router-link>
            <span class="btn btn-primary" @click="openSearch = !openSearch"><i class="bi bi-search"></i> Pesquisar</span>
        </div>
        <div v-show="openSearch" class="card fadeIn " style="margin-bottom: 1em; transition: opacity 0.5s ease;">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="cpf" class="form-label">CPF:</label>
                        <input
                            v-model="filterClient.cpf"
                            type="text"
                            v-mask="'###.###.###-##'"
                            class="form-control"
                            >
                    </div>
                    <div class="col-md-3">
                        <label for="name" class="form-label">Nome:</label>
                        <input v-model="filterClient.name" type="text" class="form-control" id="name" name="name" >
                    </div>

                    <div class="col-md-2">
                        <label for="email" class="form-label">Email:</label>
                        <input v-model="filterClient.email" type="text" class="form-control" id="email" name="email">
                    </div>
                    <div class="col-md-2">
                        <label for="birth_date" class="form-label">Data Nascimento:</label>
                        <input v-model="filterClient.birth_date" type="date" class="form-control" id="birth_date" name="birth_date">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label class="form-label">Sexo:</label>
                        <div>
                            <input v-model="filterClient.gender" type="radio" id="male" name="gender" value="M">
                            <label for="male">Masculino</label>

                            <input v-model="filterClient.gender" type="radio" id="female" name="gender" value="F">
                            <label for="female">Feminino</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="address" class="form-label">Endereço:</label>
                        <input v-model="filterClient.address" type="text" class="form-control" id="address" name="address">

                    </div>
                    <div class="col-md-2" >
                        <label for="city" class="form-label">Cidade </label>
                        <input v-model="filterClient.city" type="text" class="form-control" id="city" name="city">
                    </div>
                </div>
                <button @click="fetchItems()" class="btn btn-primary"><i class="bi bi-search"></i> Filtrar</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Email</th>
                        <th scope="col">Endereço</th>
                        <th scope="col">Cidade</th>
                        <th scope="col">Sexo</th>
                        <th scope="col" style="width: 20%;"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, i) in items.data" :key="i">
                        <td>{{ item.id }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.email }}</td>
                        <td>{{ item.address }}</td>
                        <td>{{ item.city.name }}</td>
                        <td>{{ item.gender }}</td>
                        <td>
                            <router-link :to="{ name: 'EditClient', params: { id: item.id } }"
                                class="btn btn-sm btn-outline-primary me-2"><i class="bi bi-pencil-square me-1"></i>
                                Editar</router-link>
                            <button class="btn btn-sm btn-outline-primary" @click="openAvaiableSellers(item.id)"> <i
                                class="bi bi-backpack4 me-1"></i> Vendedores</button>
                            <button class="btn btn-sm btn-outline-danger" @click="openDeleteConfirmation(item.id)"> <i
                                    class="bi bi-trash me-1"></i> Deletar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <Bootstrap5Pagination
                :data="items"
                @pagination-change-page="fetchItems"
                align="center"
                size="small"
            />
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
        aria-hidden="true" ref="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirma deleção?</h5>
                    <button type="button" class="btn-close" @click="closeModal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    Voce realmente deseja deletar?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Cancelar</button>
                    <button type="button" class="btn btn-danger" @click="confirmDelete">Deletar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Avaiable Sellers Modal -->
    <div class="modal fade" id="avaiableSellersConfirmationModal" tabindex="-1" aria-labelledby="avaiableSellersConfirmationModal" aria-hidden="true" ref="avaiableSellersModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="avaiableSellersConfirmationModal">Vendedores disponiveis para este cliente</h5>
                    <button type="button" class="btn-close" @click="closeAvaiableSellerModal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <li v-for="seller in listSellers" :key="seller.id" class="list-group-item"> {{ seller.name}}</li>
                      </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeAvaiableSellerModal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import { onMounted, ref, reactive } from 'vue';
import apiRoutes from '../../api'
import { Bootstrap5Pagination } from 'laravel-vue-pagination';
import { toast } from 'vue3-toastify';

export default {
    components:{
        Bootstrap5Pagination
    },
    setup() {
        const items = ref([]);
        const itemIdToDelete = ref(null);
        const deleteModal = ref(null);
        const avaiableSellersModal = ref(null);
        const successMessage = ref('');
        const openSearch = ref(false);
        const filterClient =  reactive({ cpf: '', name: '', birth_date: '', gender: '', address: '', state: '', city: null, email: '', per_page: 15, page: 1 });
        const listSellers = ref([]);

        const fetchItems = async (page = 1) => {
            try {
                filterClient.page = page;
                const params = new URLSearchParams();
                Object.keys(filterClient).forEach(key => {
                    if (filterClient[key]) {
                        params.append(key, filterClient[key]);
                    }
                });

                const response = await axios.get(`${apiRoutes.client.get}?${params.toString()}`);
                items.value = response.data;
            } catch (error) {
                console.error('Error fetching items:', error);
            }
        };

        const openDeleteConfirmation = (id) => {
            itemIdToDelete.value = id;
            const modalInstance = new bootstrap.Modal(deleteModal.value);
            modalInstance.show();
        };

        const closeModal = () => {
            const modalInstance = bootstrap.Modal.getInstance(deleteModal.value);
            if (modalInstance) {
                modalInstance.hide();
            }
        };

        const openAvaiableSellers = async (id ) => {
            const response = await axios.get(`${apiRoutes.sellers.byClient}${id}`);
            listSellers.value = response.data;
            itemIdToDelete.value = id;

            const modalInstance = new bootstrap.Modal(avaiableSellersModal.value);
            modalInstance.show();
        };

        const closeAvaiableSellerModal = () => {
            const modalInstance = bootstrap.Modal.getInstance(avaiableSellersModal.value);
            if (modalInstance) {
                modalInstance.hide();
            }
        };

        const confirmDelete = async () => {
            if (itemIdToDelete.value !== null) {
                await deleteItem(itemIdToDelete.value);
                toast('Deletado com sucesso', {
                    autoClose: 4500,
                    position: toast.POSITION.BOTTOM_RIGHT,
                });
                closeModal();
            }
        };

        const deleteItem = async (id) => {
            try {
                await axios.delete(`${apiRoutes.client.delete}${id}`);
                await fetchItems(filterClient.page)

            } catch (error) {
                console.error('Error deleting item:', error);
            }
        };

        onMounted(fetchItems);

        return {
            items,
            openDeleteConfirmation,
            confirmDelete,
            deleteItem,
            deleteModal,
            closeModal,
            successMessage,
            openSearch,
            filterClient,
            fetchItems,
            avaiableSellersModal,
            openAvaiableSellers,
            closeAvaiableSellerModal,
            listSellers
        };
    }
}
</script>

<style>
.fadeIn {
    -webkit-animation-name: fadeIn;
    animation-name: fadeIn;
    -webkit-animation-duration: 1s;
    animation-duration: 1s;
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
}
@-webkit-keyframes fadeIn {
    0% {opacity: 0;}
    100% {opacity: 1;}
}
@keyframes fadeIn {
    0% {opacity: 0;}
    100% {opacity: 1;}
}
</style>
