# ProjetSortir

## Enlaces
- [Version en français](README/README_FR.md)
- [English Version](README.md)

## Descripción
ENI desea desarrollar una plataforma web para sus aprendices activos y antiguos aprendices que les permita organizar salidas. La plataforma es privada y la inscripción será gestionada por el o los administradores. Las salidas y los participantes están vinculados a un campus para facilitar la organización geográfica de los eventos.

## Problema
El gran número de aprendices y su distribución en diferentes campus dificultan la organización de eventos o salidas. Los problemas identificados son:
- No hay un canal de comunicación oficial para proponer o consultar salidas.
- Las herramientas actuales no permiten gestionar las invitaciones según la ubicación geográfica o los intereses de los aprendices, ni gestionar el número de invitados ni la fecha límite de inscripción.

Una solución exitosa permitiría la organización de estas salidas y anticipar el número de participantes, el lugar de la salida y otra información necesaria para el buen desarrollo de la actividad.

## Objetivo
Establecer una plataforma web para los aprendices en formación y antiguos aprendices que permita organizar salidas durante el tiempo fuera de la formación.

## Tecnologías utilizadas
- Symfony
- PHP
- Gestión de BDD (MySQL)
- Wamp
- PhpMyAdmin
- HTML/CSS
- Bootstrap
- Javascript
- Twig
- GitHub
- PhpStorm

## Diagramas
- **Diagrama de casos de uso**: ![Use Case Diagram](README/UseCaseDiagram.png)
- **Diagrama de clases**: ![Class Diagram](README/ClassDiagram.png)

## Uso
Con esta aplicación, los usuarios pueden:
- Crear una salida
- Crear un lugar
- Modificar o eliminar una salida creada por el usuario
- Ver y unirse a una salida creada por otro usuario
- Buscar con múltiples filtros funcionales
- Ver los perfiles de otros usuarios
- Ver y modificar su propio perfil
- Cambiar su contraseña
- Iniciar sesión

### Funcionalidades

**Para los Administradores:**
- Todas las funcionalidades de los usuarios
- Agregar una nueva ciudad
- Agregar un nuevo campus
- Agregar nuevos miembros
- Gestionar ciudades (eliminar)
- Gestionar campus (eliminar)
- Gestionar miembros (modificar, eliminar, bloquear, acceder a información)
- Buscar con un filtro de nombre para cada entidad que se pueda gestionar.

## Capturas de pantalla
Aquí hay algunas capturas de pantalla para mostrar el resultado de la aplicación:
- **Añadir un usuario**: ![Add User](README/AddUser.png)
- **Gestionar usuarios**: ![Manage Usem](README/ManageUser.png)
- **Panel de administrador**: ![Admin Panel](README/AdminPanel.png)
- **Añadir y gestionar campus**: ![Add & Manage Campus](README/ManageCampus.png)
- **Añadir y gestionar ciudades**: ![Add & Manage City](README/ManageCity.png)

## Licencias
Este proyecto está bajo la licencia ENI-informatique.
