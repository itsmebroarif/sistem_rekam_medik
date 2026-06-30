# Blueprint & System Architecture - SIMRS & RME Core

This document outlines the blueprints, database schema, entity-relationship diagram (ERD), data flow diagrams (DFD), and flowcharts for the **SIMRS & RME Core** application to help developer onboarding.

---

## 1. Data Flow Diagrams (DFD)

### DFD Level 0 (Context Diagram)
The Context Diagram represents the overall boundaries of the system, showing how authenticated users (Staff, Doctor, Admin) interact with the core application and how the application integrates with external BPJS and SATUSEHAT health APIs.

```mermaid
graph TD
    User([Staff / Doctor / Admin]) -- "1. Input Credentials & CRUD data" --> System[SIMRS & RME Core System]
    System -- "2. Auth session, KPI statistics, & CRUD feedback" --> User
    System -- "3. FHIR Resource Payload (Observation, Condition)" --> SS_API[SATUSEHAT FHIR API Gateway]
    SS_API -- "4. Encounter ID & Sync status feedback" --> System
    System -- "5. BPJS Claim Payload (SEP details)" --> BPJS_API[BPJS Bridging API VClaim]
    BPJS_API -- "6. SEP Number & Claim status feedback" --> System
```

### DFD Level 1 (Detailed Process Diagram)
The DFD Level 1 details the core processes within the application and shows where data reads/writes take place relative to specific databases and actors.

```mermaid
graph TD
    subgraph Processes
        P1[1.0 Authenticate User]
        P2[2.0 Manage Patient Data]
        P3[3.0 Manage Doctor Profile]
        P4[4.0 Manage User Profiles]
        P5[5.0 Record RME SOAP]
        P6[6.0 Sync Telemetry Data]
    end

    subgraph Data Stores
        D1[(Users Store)]
        D2[(Patients Store)]
        D3[(Doctors Store)]
        D4[(Medical Records Store)]
    end

    Staff([Staff / Doctor / Admin]) -- "Credentials" --> P1
    P1 -- "Read/Write" --> D1
    P1 -- "Session & Locale" --> Staff

    Staff -- "Patient Details" --> P2
    P2 -- "Read/Write" --> D2

    Admin([Administrator]) -- "Doctor Profile" --> P3
    P3 -- "Read/Write" --> D3

    Admin -- "User/Staff Credentials" --> P4
    P4 -- "Read/Write" --> D1

    Doctor([Doctor / Admin]) -- "SOAP Record" --> P5
    P5 -- "Read/Write" --> D4

    P6 -- "Read medical records" --> D4
    P6 -- "Simulate Bridging SEP" --> BPJS[BPJS VClaim API]
    P6 -- "FHIR Encounter" --> SS[SATUSEHAT FHIR API]
    BPJS -- "Sync Response" --> P6
    SS -- "Sync Response" --> P6
    P6 -- "Update Status" --> D4
```

---

## 2. Database Schema

The database consists of four primary tables mapping user authentication, medical entities, and SOAP clinical record transactions.

### 1. Table: `users`
Stores user credentials and roles for logging into the SIMRS administration dashboard.
| Column | Data Type | Attributes | Description |
| :--- | :--- | :--- | :--- |
| `id` | bigint | Primary Key, Auto Increment | Unique user identifier. |
| `name` | varchar(255) | Not Null | Full name of the user/staff. |
| `email` | varchar(255) | Unique, Not Null | Email used for logging in. |
| `phone` | varchar(255) | Nullable | Phone number of the staff. |
| `duty_address`| varchar(255) | Nullable | Specific station or room assigned. |
| `profile_photo`| varchar(255) | Nullable | Filepath to profile photo. |
| `password` | varchar(255) | Not Null | Hashed password. |
| `role` | varchar(255) | Default: `'staff'` | Role: `'admin'`, `'doctor'`, or `'staff'`. |
| `remember_token`| varchar(100) | Nullable | Login session token. |
| `created_at` | timestamp | Nullable | Timestamp of creation. |
| `updated_at` | timestamp | Nullable | Timestamp of last update. |

### 2. Table: `patients`
Stores demographic data for registered hospital patients.
| Column | Data Type | Attributes | Description |
| :--- | :--- | :--- | :--- |
| `id` | bigint | Primary Key, Auto Increment | Unique patient identifier. |
| `patient_number`| varchar(255) | Unique, Not Null | Unique RM Code (e.g. `RM-XXXXXX`). |
| `name` | varchar(255) | Not Null | Full name of the patient. |
| `nik` | varchar(16) | Unique, Not Null | Indonesian national identity number. |
| `gender` | enum('L','P') | Not Null | Gender: `'L'` (Male), `'P'` (Female). |
| `birth_date` | date | Not Null | Patient date of birth. |
| `phone` | varchar(255) | Nullable | Contact phone number. |
| `address` | text | Nullable | Resident address details. |
| `created_at` | timestamp | Nullable | Timestamp of creation. |
| `updated_at` | timestamp | Nullable | Timestamp of last update. |

### 3. Table: `doctors`
Stores professional profiles for medical practitioners.
| Column | Data Type | Attributes | Description |
| :--- | :--- | :--- | :--- |
| `id` | bigint | Primary Key, Auto Increment | Unique doctor identifier. |
| `doctor_number`| varchar(255) | Unique, Not Null | Unique registration code. |
| `name` | varchar(255) | Not Null | Full name of the doctor. |
| `specialization`| varchar(255) | Not Null | Area of expertise (e.g. Cardiologist). |
| `phone` | varchar(255) | Nullable | Contact phone number. |
| `duty_address`| varchar(255) | Nullable | Practice room or wing code. |
| `profile_photo`| varchar(255) | Nullable | Filepath to profile image. |
| `created_at` | timestamp | Nullable | Timestamp of creation. |
| `updated_at` | timestamp | Nullable | Timestamp of last update. |

### 4. Table: `medical_records`
Stores patient Electronic Medical Records (RME) formatted under standard SOAP protocols.
| Column | Data Type | Attributes | Description |
| :--- | :--- | :--- | :--- |
| `id` | bigint | Primary Key, Auto Increment | Unique record identifier. |
| `patient_id` | bigint | Foreign Key, Not Null | References `id` on `patients` (cascade delete). |
| `doctor_id` | bigint | Foreign Key, Not Null | References `id` on `doctors` (cascade delete). |
| `record_date` | datetime | Not Null | Exact date and time of assessment. |
| `subjective` | text | Not Null | **Subjective (S)**: Patient complaints. |
| `objective` | text | Not Null | **Objective (O)**: Vital signs & exams. |
| `assessment` | text | Not Null | **Assessment (A)**: Diagnosis conclusions. |
| `planning` | text | Not Null | **Planning (P)**: Therapy, treatments, dosage. |
| `diagnosis` | varchar(255) | Not Null | Standard primary diagnosis. |
| `treatment` | text | Nullable | Extra therapy remarks/prescriptions. |
| `bpjs_sync_status`| enum('pending','synced','failed')| Default: `'pending'` | Bridging status to BPJS VClaim API. |
| `satusehat_sync_status`| enum('pending','synced','failed')| Default: `'pending'` | Bridging status to SATUSEHAT FHIR API. |
| `created_at` | timestamp | Nullable | Timestamp of creation. |
| `updated_at` | timestamp | Nullable | Timestamp of last update. |

---

## 3. Entity Relationship Diagram (ERD)

The logical data modeling exhibits a **One-to-Many** relationship starting from both `patients` and `doctors` to `medical_records`. A patient can have multiple medical records, and a doctor can record multiple medical records.

```mermaid
erDiagram
    users {
        bigint id PK
        string name
        string email UK
        string phone
        string duty_address
        string profile_photo
        string password
        string role
        string remember_token
        timestamp created_at
        timestamp updated_at
    }
    patients {
        bigint id PK
        string patient_number UK
        string name
        string nik UK
        enum gender
        date birth_date
        string phone
        text address
        timestamp created_at
        timestamp updated_at
    }
    doctors {
        bigint id PK
        string doctor_number UK
        string name
        string specialization
        string phone
        string duty_address
        string profile_photo
        timestamp created_at
        timestamp updated_at
    }
    medical_records {
        bigint id PK
        bigint patient_id FK
        bigint doctor_id FK
        datetime record_date
        text subjective
        text objective
        text assessment
        text planning
        string diagnosis
        text treatment
        enum bpjs_sync_status
        enum satusehat_sync_status
        timestamp created_at
        timestamp updated_at
    }

    patients ||--o{ medical_records : "associated_with"
    doctors ||--o{ medical_records : "recorded_by"
```

---

## 4. System Flowcharts

### 1. Unified Authentication & Language Switching Flow
This flowchart details how the new single-page sliding auth card shifts and manages history push states without browser reloads.

```mermaid
graph TD
    Start([User opens Auth Page]) --> Path{Path URL?}
    Path -- "/login" --> ShowLogin[Show Login Slide]
    Path -- "/register" --> ShowReg[Show Register Slide]
    
    ShowLogin --> ToggleReg[Click Register Link] --> SlideReg[CSS Slide Right to Register] --> SwitchRegUrl[URL becomes /register]
    ShowReg --> ToggleLogin[Click Login Link] --> SlideLogin[CSS Slide Left to Login] --> SwitchLoginUrl[URL becomes /login]
    
    SwitchRegUrl --> SubmitReg[Submit Register Form]
    SwitchLoginUrl --> SubmitLogin[Submit Login Form]
    
    SubmitReg --> ValReg{Validation OK?}
    ValReg -- No --> RedirectReg[Redirect back to /register with errors] --> ShowReg
    ValReg -- Yes --> SaveUser[Save User to Database] --> RedirectLogin[Redirect to /login with success alert] --> ShowLogin
    
    SubmitLogin --> ValLogin{Credentials OK?}
    ValLogin -- No --> RedirectLoginErr[Redirect back to /login with errors] --> ShowLogin
    ValLogin -- Yes --> SetSession[Create Session & Set Locale] --> OpenDashboard[Redirect to Dashboard]
```

### 2. SOAP Recording & API Sync Flow
This flowchart traces how a doctor enters a new RME and how data bridging is synchronized with BPJS and SATUSEHAT.

```mermaid
graph TD
    StartSOAP([Doctor enters SOAP details]) --> SaveDB[Save Record to local DB as Pending]
    SaveDB --> ShowDashboard[Display Record on Dashboard]
    
    ShowDashboard --> SyncBPJS[Click Sync BPJS] --> AjaxBPJS[AJAX Bridge BPJS]
    AjaxBPJS --> ResBPJS{Simulated OK?}
    ResBPJS -- Yes --> UpdateBPJS[Update local db status to synced] --> ShowSuccessBPJS[Show success alert & updates UI]
    ResBPJS -- No --> FailedBPJS[Update local db status to failed] --> ShowErrorBPJS[Show error alert]

    ShowDashboard --> SyncSS[Click Sync SATUSEHAT] --> AjaxSS[AJAX FHIR Upload]
    AjaxSS --> ResSS{Simulated OK?}
    ResSS -- Yes --> UpdateSS[Update local db status to synced] --> ShowSuccessSS[Show success alert & updates UI]
    ResSS -- No --> FailedSS[Update local db status to failed] --> ShowErrorSS[Show error alert]
```
