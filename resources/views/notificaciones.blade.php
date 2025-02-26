@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'notificaciones' @endphp
@endsection
 
@section('content')

 <style>
    .notification-container {
        width: 90%;
        max-width: 300px;
        background-color: #f9f9f9;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 15px;
        right: 10px;
        bottom: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        display: none;
        z-index: 2000; 
    }

    .notification-container h2 {
        margin: 0 0 10px 0;
        font-size: 1.2rem;
    }

    .notification {
        background-color: #e0f7fa;
        border: 1px solid #b2ebf2;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 5px;
        font-size: 0.9rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notification .close {
        color: #f44336;
        cursor: pointer;
        font-weight: bold;
        margin-left: 10px;
        font-size: 1.2rem;
    }

    button#notificationButton { 
        margin: 20px;
        padding: 10px 15px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
    }

    button#notificationButton:hover {
        background-color: #0056b3;
    }

    @media (max-width: 600px) {
        .notification-container {
            width: 95%;
            right: 5px;
            padding: 10px;
        }

        .notification {
            font-size: 0.8rem;
            padding: 8px;
        }
    }

    .notification-container {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    margin: 20px;
    background-color: #f9f9f9;
}

.notification {
    padding: 10px;
    margin: 5px 0;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 3px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.close {
    cursor: pointer;
    color: #ff0000;
    font-weight: bold;
}

.close:hover {
    color: #cc0000;
}
</style>

<div class="notification-container" id="notificationContainer">
    <h2>Notificaciones</h2>
    <ul id="notificationList"></ul>
</div>

@can('admin.update')
 <input type="text" id="notificationMessage" placeholder="Escribe tu notificación aquí" style="margin: 20px; padding: 10px; width: 80%;">
 <button id="notificationButton" onclick="addNotification()">Agregar Notificación</button> <!-- ID específico agregado -->
@endcan

 <script>
    const notificationList = document.getElementById("notificationList");
    const notificationContainer = document.getElementById("notificationContainer");

    window.onload = function() {
        const notifications = JSON.parse(localStorage.getItem("notifications")) || [];
        notifications.forEach(notification => {
            addNotificationToList(notification);
        });
    };

    function addNotification() {
        const input = document.getElementById("notificationMessage");
        const notificationText = input.value.trim();
        const currentTime = new Date().toLocaleTimeString(); // Obtener la hora actual

        if (notificationText) {
            const fullNotification = `${notificationText} a las ${currentTime}`; // Concatenar el mensaje con la hora
            addNotificationToList(fullNotification);
            saveNotification(fullNotification);
            input.value = ""; 
        } else {
            alert("Por favor, ingresa un mensaje para la notificación.");
        }
    }

    function addNotificationToList(notificationText) {
        const li = document.createElement("li");
        li.className = "notification";
        li.innerHTML = `${notificationText} <span class="close" onclick="removeNotification(this)">✖</span>`;
        notificationList.appendChild(li);
        notificationContainer.style.display = "block";
    }

    function saveNotification(notificationText){
        const notifications = JSON.parse(localStorage.getItem("notifications")) || [];
        notifications.push(notificationText);
        localStorage.setItem("notifications", JSON.stringify(notifications));
    }

    function removeNotification(element) {
        const notification = element.parentElement;
        notificationList.removeChild(notification);

        const notificationText = notification.innerText.replace("✖", "").trim();
        removeNotificationFromStorage(notificationText);

        if (notificationList.children.length === 0) {
            notificationContainer.style.display = "none";
        }
    }

    function removeNotificationFromStorage(notificationText) {
        let notifications = JSON.parse(localStorage.getItem("notifications")) || [];
        notifications = notifications.filter(notification => notification !== notificationText);
        localStorage.setItem("notifications", JSON.stringify(notifications));
    }
</script>
@endsection
