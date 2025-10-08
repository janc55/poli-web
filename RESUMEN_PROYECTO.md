# Resumen del Proyecto Policonsultorio UNIOR

- **Descripción general**: Sistema de gestión médica integral para un policonsultorio universitario que maneja citas médicas, historiales clínicos, gestión de doctores y pacientes, con interfaz administrativa moderna y sitio web público.

- **Tecnologías utilizadas**: 
  - **Backend**: PHP 8.2+, Laravel 12.0, Eloquent ORM
  - **Frontend**: Blade templates, Tailwind CSS 4.1, Alpine.js 3.x, Vite 6.2
  - **Panel Admin**: Filament 3.3
  - **Base de Datos**: SQLite (por defecto), soporte para MySQL/PostgreSQL
  - **Autenticación**: Laravel Sanctum + Spatie Laravel Permission 6.20
  - **Testing**: PHPUnit 11.5

## Arquitectura

- **Estructura de directorios**: 
  - `/app` - Lógica de negocio y modelos
  - `/app/Filament` - Recursos del panel administrativo
  - `/resources/views` - Vistas Blade con componentes reutilizables
  - `/database` - Migraciones, seeders y factories
  - `/routes` - Definición de rutas web y API

- **Componentes clave**: 
  - Panel administrativo Filament con recursos CRUD
  - Sistema de roles y permisos granular
  - API RESTful para operaciones médicas
  - Frontend público con diseño responsive

## Base de Datos

- **Tipo de BD**: Relacional (SQLite/MySQL/PostgreSQL) con Eloquent ORM
- **Tablas principales**:

| Tabla | Campos | Relaciones |
|-------|--------|------------|
| `users` | id, name, email, password, email_verified_at, remember_token, timestamps | PK para autenticación |
| `patients` | id, user_id, first_name, last_name, ci, ci_extension, birth_date, gender, address, phone, timestamps | FK → users, 1:1 |
| `doctors` | id, user_id, first_name, last_name, ci, ci_extension, birth_date, gender, address, phone, license_number, bio, timestamps | FK → users, 1:1 |
| `service_types` | id, name, timestamps | - |
| `services` | id, name, service_type_id, timestamps | FK → service_types, N:1 |
| `appointments` | id, patient_id, user_id, doctor_id, service_id, scheduled_at, status, notes, timestamps | FK → patients, users, doctors, services |
| `medical_records` | id, patient_id, doctor_id, date, symptoms, diagnosis, treatment, notes, vitals, timestamps | FK → patients, users |
| `doctor_schedules` | id, doctor_id, service_id, day_of_week, start_time, end_time, appointment_duration, timestamps | FK → doctors, services |
| `doctor_service` | doctor_id, service_id | Tabla pivot N:N |
| `roles` | id, name, guard_name, timestamps | - |
| `permissions` | id, name, guard_name, timestamps | - |
| `model_has_roles` | role_id, model_type, model_id | Tabla pivot roles |
| `model_has_permissions` | permission_id, model_type, model_id | Tabla pivot permisos |

- **Consultas/Migraciones**: 11 migraciones principales incluyendo tablas de autenticación, médicas y de permisos
- **Seeds**: Datos iniciales para roles, permisos, usuarios demo y servicios

## Modelos y Entidades

### Modelos Principales

| Modelo | Atributos | Relaciones | Métodos |
|--------|-----------|------------|---------|
| **User** | name, email, password, email_verified_at | hasMany(appointments), hasOne(patient), hasRoles | - |
| **Patient** | first_name, last_name, ci, birth_date, gender, address, phone | belongsTo(user), hasMany(appointments, medicalRecords) | getFullNameAttribute() |
| **Doctor** | first_name, last_name, ci, license_number, bio, birth_date, gender, address, phone | belongsTo(user), belongsToMany(services), hasMany(schedules) | - |
| **Service** | name | belongsTo(serviceType), belongsToMany(doctors), hasMany(appointments) | - |
| **ServiceType** | name | hasMany(services) | - |
| **Appointment** | patient_id, user_id, doctor_id, service_id, scheduled_at, status, notes | belongsTo(patient, user, doctor, service) | - |
| **MedicalRecord** | patient_id, doctor_id, date, symptoms, diagnosis, treatment, notes, vitals | belongsTo(patient, doctor) | - |
| **DoctorSchedule** | doctor_id, service_id, day_of_week, start_time, end_time, appointment_duration | belongsTo(doctor, service) | - |

### Validaciones y Lógica
- **Casts**: Fechas como `date`/`datetime`, `vitals` como `array`
- **Fillable**: Campos permitidos para asignación masiva
- **Relaciones**: Eloquent relationships bien definidas
- **Policies**: Control de acceso granular por rol y permiso

## Funcionalidad

### Módulos/Features Existentes

- **Gestión de Usuarios**: CRUD completo con roles y permisos
- **Gestión de Pacientes**: Registro, edición y consulta de historiales
- **Gestión de Doctores**: Perfiles médicos con servicios asignados
- **Gestión de Citas**: Programación, confirmación y cancelación
- **Historiales Médicos**: Registro de consultas con síntomas, diagnóstico y tratamiento
- **Horarios Médicos**: Configuración de disponibilidad por servicio
- **Tipos de Servicio**: Categorización (Especialidades, Imagenología, Laboratorio)
- **Panel Administrativo**: Interfaz Filament con widgets y estadísticas

### Flujo de Trabajo
1. **Registro/Login** → Autenticación por rol
2. **Gestión de Pacientes** → Crear/editar perfiles médicos
3. **Programación de Citas** → Seleccionar paciente, servicio, doctor y horario
4. **Consulta Médica** → Registrar síntomas, diagnóstico y tratamiento
5. **Seguimiento** → Historiales y estadísticas

### Pruebas y Cobertura
- **Framework**: PHPUnit 11.5 configurado
- **Tests**: Estructura básica con test de ejemplo
- **Cobertura**: Configuración inicial sin tests específicos implementados

## Configuración y Entorno

### Variables de Entorno
- `APP_NAME` - Nombre de la aplicación
- `APP_ENV` - Entorno (local/production)
- `APP_DEBUG` - Modo debug
- `APP_URL` - URL base
- `APP_LOCALE` - Idioma (español por defecto)
- `DB_CONNECTION` - Tipo de BD (sqlite/mysql/pgsql)
- `DB_DATABASE` - Nombre de la BD
- `DB_HOST`, `DB_PORT`, `DB_USERNAME`, `DB_PASSWORD` - Configuración BD

### Despliegue
- **Servidor**: Laravel Sail para desarrollo local
- **Build**: Vite para assets frontend
- **Comandos**: `composer install`, `php artisan migrate`, `npm run build`
- **Scripts**: `composer dev` para desarrollo simultáneo

### Dependencias Externas
- **Filament**: Panel administrativo moderno
- **Spatie Permission**: Sistema de roles y permisos
- **Tailwind CSS**: Framework CSS utility-first
- **Alpine.js**: Framework JavaScript ligero

## Pendientes y Notas

### Issues Conocidos
- Falta implementar tests unitarios y de integración
- No hay validaciones personalizadas en modelos
- Falta implementar notificaciones por email/SMS
- No hay sistema de auditoría de cambios

### Sugerencias para Próximos Módulos
- **Facturación**: Sistema de cobros y pagos
- **Farmacia**: Gestión de medicamentos y recetas
- **Laboratorio**: Resultados de análisis clínicos
- **Telemedicina**: Consultas virtuales
- **Reportes**: Estadísticas y análisis médicos
- **Mobile App**: Aplicación móvil para pacientes
- **Integración**: APIs para sistemas externos (hospitales, seguros)

### Características Destacadas
- **Multiidioma**: Soporte para español e inglés
- **Responsive**: Diseño adaptativo para móviles
- **Seguridad**: Sistema de permisos granular
- **UX**: Interfaz moderna con Tailwind y Alpine.js
- **Escalabilidad**: Arquitectura modular y extensible

---

*Documento generado para continuar el desarrollo de módulos adicionales del proyecto Policonsultorio UNIOR*
