# 🚀 GUÍA DE CONFIGURACIÓN Y TRABAJO EN EQUIPO - SISTEMA ISTPET

**Proyecto:** Sistema de Carnetización y Control de Accesos
**Equipo:** 3 personas
**Repositorio:** https://github.com/[TU-USUARIO]/sistema-carnetizacion-istpet

---

## 📋 TABLA DE CONTENIDOS

1. [Requisitos Previos](#1-requisitos-previos)
2. [Configuración Inicial (SOLO LA PRIMERA VEZ)](#2-configuración-inicial)
3. [Cómo Trabajar con Ramas](#3-cómo-trabajar-con-ramas)
4. [Comandos Git Esenciales](#4-comandos-git-esenciales)
5. [Reglas del Equipo](#5-reglas-del-equipo)
6. [Solución de Problemas](#6-solución-de-problemas)

---

## 1. REQUISITOS PREVIOS

### ✅ Software necesario:

- **XAMPP** (Apache + MySQL + PHP 8.2+)
- **Composer** (gestor de dependencias PHP)
- **Git** (control de versiones)
- **VS Code** (editor de código - recomendado)
- **Cuenta de GitHub**

### Verificar instalaciones:

Abre PowerShell o CMD y ejecuta:

```bash
# Verificar PHP
php -v
# Debe mostrar: PHP 8.2.x

# Verificar Composer
composer --version
# Debe mostrar: Composer version 2.x

# Verificar Git
git --version
# Debe mostrar: git version 2.x
```

Si falta algo, instálalo antes de continuar.

---

## 2. CONFIGURACIÓN INICIAL

### 🔧 PASO 1: Configurar Git (PRIMERA VEZ)

Abre PowerShell y ejecuta:

```bash
# Configurar tu nombre
git config --global user.name "Tu Nombre Completo"

# Configurar tu email (el de GitHub)
git config --global user.email "tu-email@gmail.com"

# Verificar configuración
git config --list
```

---

### 📥 PASO 2: Clonar el Repositorio

```bash
# 1. Ir a la carpeta de XAMPP
cd C:\xampp\htdocs

# 2. Clonar el proyecto (REEMPLAZA [TU-USUARIO] con el usuario real)
git clone https://github.com/[TU-USUARIO]/sistema-carnetizacion-istpet.git

# 3. Entrar al proyecto
cd sistema-carnetizacion-istpet
```

**Si te pide login de GitHub:**
- Usuario: tu-usuario-github
- Password: tu-contraseña-github

---

### 📦 PASO 3: Instalar Dependencias de Laravel

```bash
# Esto instala todas las librerías necesarias
composer install
```

⏳ **Esto puede tardar 2-3 minutos. Es normal.**

---

### ⚙️ PASO 4: Configurar Variables de Entorno

```bash
# Copiar archivo de ejemplo
copy .env.example .env

# Generar llave de aplicación
php artisan key:generate
```

Ahora edita el archivo `.env` con tu editor:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=carnetizacion_istpet
DB_USERNAME=root
DB_PASSWORD=           ← DÉJALO VACÍO (o pon tu password si tienes)
```

---

### 🗄️ PASO 5: Configurar Base de Datos

#### A. Crear la base de datos:

1. Abre: `http://localhost/phpmyadmin`
2. Click en **"Nueva"** (izquierda)
3. Nombre: `carnetizacion_istpet`
4. Cotejamiento: `utf8mb4_unicode_ci`
5. Click **"Crear"**

#### B. Importar datos:

1. Selecciona la base de datos `carnetizacion_istpet`
2. Click en pestaña **"Importar"**
3. Click **"Seleccionar archivo"**
4. Busca: `C:\xampp\htdocs\sistema-carnetizacion-istpet\database\carnetizacion_istpet.sql`
5. Click **"Continuar"**

✅ **Debe decir: "Importación finalizada correctamente"**

---

### 🔗 PASO 6: Crear Enlace Simbólico

```bash
php artisan storage:link
```

Esto permite que las fotos se vean correctamente.

---

### 🚀 PASO 7: Probar el Sistema

```bash
# Iniciar servidor
php artisan serve
```

Abre tu navegador: `http://127.0.0.1:8000`

**Si todo está bien, debes ver la página de login** ✅

**Credenciales de prueba:**
- **Admin:** usuario: `admin` / password: `admin123`
- **Profesor:** usuario: `profesor1` / password: `profesor123`
- **Estudiante:** cédula: `1750123456` / password: `estudiante123`

---

## 3. CÓMO TRABAJAR CON RAMAS

### 🌿 Estructura de Ramas

```
main (producción - NO TOCAR)
  │
  └── develop (desarrollo - BASE PARA TRABAJAR)
        │
        ├── feature/importacion-excel (Kevin)
        ├── feature/reportes-pdf (Compañero 1)
        └── feature/notificaciones (Compañero 2)
```

---

### 📝 FLUJO DIARIO DE TRABAJO

#### **CADA VEZ que vayas a trabajar:**

```bash
# 1. Ir a la carpeta del proyecto
cd C:\xampp\htdocs\sistema-carnetizacion-istpet

# 2. Asegurarte de estar en develop
git checkout develop

# 3. Traer últimos cambios del equipo
git pull origin develop

# 4. Crear tu rama de trabajo (SOLO LA PRIMERA VEZ de esa tarea)
git checkout -b feature/nombre-de-tu-tarea

# Ejemplo:
# git checkout -b feature/importacion-excel
# git checkout -b feature/reportes-pdf
# git checkout -b feature/sistema-notificaciones
```

---

#### **MIENTRAS TRABAJAS:**

```bash
# Ver qué archivos has modificado
git status

# Agregar TUS cambios
git add .

# Guardar cambios con un mensaje descriptivo
git commit -m "feat: descripción de lo que hiciste"

# Ejemplos de mensajes:
# git commit -m "feat: crear controlador de importación"
# git commit -m "feat: agregar validación de Excel"
# git commit -m "fix: corregir error en formulario"

# Subir TU rama a GitHub
git push origin feature/nombre-de-tu-tarea
```

**Puedes hacer esto VARIAS VECES mientras trabajas.**

---

#### **CUANDO TERMINES TU TAREA:**

1. **Ve a GitHub**: `https://github.com/[TU-USUARIO]/sistema-carnetizacion-istpet`

2. Verás un banner amarillo:
   ```
   feature/tu-rama had recent pushes
   [Compare & pull request]
   ```

3. **Click en "Compare & pull request"**

4. **Configurar Pull Request:**
   - **Base:** `develop` ⚠️ **MUY IMPORTANTE**
   - **Compare:** `feature/tu-rama`
   - **Title:** Nombre descriptivo de tu trabajo
   - **Description:** Qué hiciste y cómo probarlo

   **Ejemplo:**
   ```
   ## Cambios realizados
   - ✅ Creado controlador de importación
   - ✅ Agregada validación de archivos Excel
   - ✅ Implementada importación a BD
   
   ## Cómo probar
   1. Ir a /admin/estudiantes/importar
   2. Subir archivo Excel de prueba
   3. Verificar que se crean los estudiantes
   ```

5. **Click "Create pull request"**

6. **Avisar al equipo** por WhatsApp/Discord:
   ```
   "Hice PR para [nombre de la funcionalidad], 
    por favor revisen 🙏"
   ```

---

#### **REVISAR PULL REQUESTS DE TUS COMPAÑEROS:**

Cuando un compañero crea un Pull Request:

1. Ve al PR en GitHub
2. Click en pestaña **"Files changed"**
3. Revisa el código
4. Si está bien:
   - Click en **"Review changes"**
   - Selecciona **"Approve"**
   - Click **"Submit review"**
5. Click en **"Merge pull request"**
6. Click **"Confirm merge"**
7. Opcional: Click **"Delete branch"** (limpia la rama)

---

#### **ACTUALIZAR TU CÓDIGO CON LOS CAMBIOS DEL EQUIPO:**

**CADA VEZ que alguien haga merge, TODOS deben actualizar:**

```bash
# 1. Volver a develop
git checkout develop

# 2. Traer cambios nuevos
git pull origin develop
```

**Ahora tienes el trabajo de TODOS** ✅

---

## 4. COMANDOS GIT ESENCIALES

### 📌 Comandos Básicos

```bash
# Ver en qué rama estás
git branch

# Ver TODAS las ramas (incluidas de GitHub)
git branch -a

# Cambiar de rama
git checkout nombre-rama

# Ver estado actual (archivos modificados)
git status

# Ver historial de commits
git log --oneline

# Ver últimos 5 commits
git log --oneline -5
```

---

### 🔄 Comandos de Sincronización

```bash
# Traer cambios de GitHub
git pull origin nombre-rama

# Subir cambios a GitHub
git push origin nombre-rama

# Traer TODAS las ramas de GitHub
git fetch origin
```

---

### 💾 Comandos de Guardado

```bash
# Agregar UN archivo
git add nombre-archivo.php

# Agregar TODOS los archivos modificados
git add .

# Hacer commit
git commit -m "descripción del cambio"

# Agregar Y hacer commit en un solo comando
git commit -am "descripción del cambio"
```

---

### 🆘 Comandos de Emergencia

```bash
# Descartar cambios de UN archivo (NO guardados)
git checkout -- nombre-archivo.php

# Descartar TODOS los cambios (NO guardados)
git checkout .

# Ver diferencias de lo que cambiaste
git diff

# Ver qué archivos cambiaste
git status
```

---

## 5. REGLAS DEL EQUIPO

### ✅ SÍ DEBES:

1. **Siempre trabajar en una rama** (nunca en `main` o `develop` directo)
2. **Hacer Pull Request** para fusionar tu código
3. **Actualizar develop** antes de crear nueva rama
4. **Hacer commits pequeños y frecuentes**
5. **Escribir mensajes descriptivos** en commits
6. **Revisar PRs de tus compañeros**
7. **Avisar cuando hagas un PR**
8. **Probar tu código** antes de hacer PR

---

### ❌ NO DEBES:

1. ~~Hacer `push` directo a `main`~~ (solo con PR)
2. ~~Hacer `push` directo a `develop`~~ (solo con PR)
3. ~~Trabajar sin crear rama~~
4. ~~Hacer commits enormes~~ (mejor varios pequeños)
5. ~~Modificar archivos de tus compañeros sin avisar~~
6. ~~Hacer merge de tu propio PR sin revisión~~
7. ~~Subir archivos `.env` o contraseñas~~

---

### 📝 Convención de Nombres

#### Ramas:
```
feature/nombre-funcionalidad    → Nueva característica
fix/nombre-bug                  → Corrección de error
docs/actualizar-readme          → Documentación
refactor/optimizar-consultas    → Mejora de código
```

#### Commits:
```bash
git commit -m "feat: agregar módulo de reportes"
git commit -m "fix: corregir error en login"
git commit -m "docs: actualizar README"
git commit -m "refactor: optimizar consultas SQL"
```

**Prefijos:**
- `feat:` → Nueva funcionalidad
- `fix:` → Corrección de bug
- `docs:` → Documentación
- `refactor:` → Mejora de código
- `style:` → Cambios de formato (CSS, indentación)
- `test:` → Agregar tests

---

## 6. SOLUCIÓN DE PROBLEMAS

### ❓ Error: "Your branch is behind"

**Significa:** Tu rama está desactualizada.

**Solución:**
```bash
git pull origin develop
```

---

### ❓ Error: "Merge conflict"

**Significa:** Tú y un compañero modificaron el mismo archivo.

**Solución:**

1. Git te mostrará el archivo con conflicto:
   ```php
   <<<<<<< HEAD
   Tu código
   =======
   Código de tu compañero
   >>>>>>> rama-compañero
   ```

2. **Edita el archivo** y decide qué código mantener

3. **Elimina las marcas** (`<<<<<<<`, `=======`, `>>>>>>>`)

4. **Guarda el archivo**

5. Continúa:
   ```bash
   git add .
   git commit -m "fix: resolver conflicto en archivo X"
   git push origin tu-rama
   ```

---

### ❓ Error: "fatal: not a git repository"

**Significa:** No estás en la carpeta del proyecto.

**Solución:**
```bash
cd C:\xampp\htdocs\sistema-carnetizacion-istpet
```

---

### ❓ "¿Cómo vuelvo a una rama anterior?"

```bash
# Ver ramas disponibles
git branch

# Cambiar de rama
git checkout nombre-rama
```

---

### ❓ "Hice cambios pero quiero descartarlos"

```bash
# Descartar cambios de UN archivo
git checkout -- nombre-archivo.php

# Descartar TODOS los cambios
git checkout .
```

---

### ❓ "¿Cómo actualizo Laravel después de clonar?"

```bash
# 1. Instalar dependencias
composer install

# 2. Copiar .env
copy .env.example .env

# 3. Generar key
php artisan key:generate

# 4. Link storage
php artisan storage:link

# 5. Limpiar cachés
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

### ❓ "No puedo hacer push"

**Posibles causas y soluciones:**

1. **No has hecho commit:**
   ```bash
   git add .
   git commit -m "descripción"
   git push origin tu-rama
   ```

2. **La rama no existe en GitHub:**
   ```bash
   git push -u origin tu-rama
   ```

3. **Necesitas actualizar:**
   ```bash
   git pull origin develop
   git push origin tu-rama
   ```

---

## 📞 CONTACTO Y AYUDA

### Grupo de WhatsApp/Discord:
[AGREGAR LINK DEL GRUPO]

### Si tienes problemas:

1. **Captura pantalla** del error
2. **Copia el comando** que ejecutaste
3. **Envía al grupo** pidiendo ayuda
4. Alguien del equipo te ayudará

---

## 📚 RECURSOS ADICIONALES

### Documentación:
- **Laravel:** https://laravel.com/docs/11.x
- **Git:** https://git-scm.com/doc
- **GitHub:** https://docs.github.com

### Tutoriales:
- Git Básico: https://www.youtube.com/watch?v=HiXLkL42tMU
- Laravel: https://www.youtube.com/watch?v=MYyJ4PuL4pY

---

## ✅ CHECKLIST DE CONFIGURACIÓN

Marca lo que ya completaste:

```
□ Instalé XAMPP
□ Instalé Composer
□ Instalé Git
□ Configuré Git con mi nombre y email
□ Cloné el repositorio
□ Instalé dependencias (composer install)
□ Configuré .env
□ Creé la base de datos
□ Importé el SQL
□ Hice php artisan storage:link
□ Probé que funcione (php artisan serve)
□ Entiendo cómo crear ramas
□ Entiendo cómo hacer commits
□ Entiendo cómo hacer Pull Requests
```

---

## 🎯 FLUJO RÁPIDO (RESUMEN)

### Primera vez:
```bash
1. git clone [URL]
2. composer install
3. copy .env.example .env
4. php artisan key:generate
5. Importar BD
6. php artisan storage:link
```

### Cada día de trabajo:
```bash
1. git checkout develop
2. git pull origin develop
3. git checkout -b feature/mi-tarea (solo primera vez)
4. ... TRABAJAR ...
5. git add .
6. git commit -m "descripción"
7. git push origin feature/mi-tarea
8. Crear Pull Request en GitHub
```

### Después de merge de compañeros:
```bash
1. git checkout develop
2. git pull origin develop
```

---

**¡Éxito en el proyecto! 🚀**

**Última actualización:** Enero 2025
**Equipo:** Kevin + Compañeros ISTPET
