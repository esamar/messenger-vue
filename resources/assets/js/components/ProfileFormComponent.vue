<template>
  <b-container>
    <b-form class="m-2" action="" method="post" enctype="multipart/form-data">
      
      <input type="hidden" name="_token" v-model="csrfToken">
      
      <b-form-group label="Correo electrónico:"
        description="We'll never share your email with anyone else."
      >
        <b-form-input 
          v-model="user.email"
          name="email"
          type="email"
          disabled
          placeholder="Ingrese correeo"
        ></b-form-input>
      </b-form-group>

      <b-form-group label="Nambre:">
        <b-form-input
          type="text"
          name="name"
          v-model="user.name"
          required
          placeholder="Ingrese nombres"
        ></b-form-input>
      </b-form-group>

      <b-form-group label="Nueva contraseña (sólo si desea modificar):">
        <b-form-input
          type="password"
          name="password"
          placeholder="Ingrese una nueva contraseña"
        ></b-form-input>
      </b-form-group>

      <b-form-group label="Imgen de perfil:">
        
        <b-img :src="image" rounded="circle" width="60" height="60" alt="Imagen de perfil" class="m-1"></b-img>
        
        <b-form-file
          name="image"
          placeholder="Seleccione una nueva imagen"
        ></b-form-file>
      </b-form-group>      

      <b-button type="submit" variant="primary">Guardar cambios</b-button>
    </b-form>
  
  </b-container>

</template>

<script>
  export default {
    props: {
      user : Object,
      csrfToken : String
    },
    data() {
      return {
      }
    },
    computed: {
      image(){
        return `/users/${this.user.image}`;
      }
    }
  }
</script>