    @extends('principal')
    @section('contenido')
        <template v-if="menu==0">
            <!-- <example-component></example-component> -->
        </template>
        <template v-if="menu==1">
            <categoria></categoria>
        </template>
        <template v-if="menu==2">
            <medida></medida>
        </template>
        <template v-if="menu==3">
            <producto></producto>
        </template>
        <template v-if="menu==4">
            <espacio></espacio>
        </template>
        <template v-if="menu==10">
            <persona></persona>
        </template>
    @endsection