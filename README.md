Start server `symfony.exe server:start`


Start symfony project:
`symfony new --webapp conciergerie76`

dans ``.env`` add database link : 
``DATABASE_URL="mysql://root:@127.0.0.1:3306/conciergerie76"``

## Mapping Library :
Leaflet.js (Free & lightweight, recommended for OpenStreetMap)

🎯 Features Implemented
✅ Locate the user using geolocation ('Find my location' button)
✅ Let the user click anywhere on the map to set a destination
✅ Calculate and show the distance in meters
✅ Display the distance inside a popup 
✅ Draw a blue line between the user and the selected location

✅ User can search a location by name (e.g., "Paris, France")
✅ The map finds and marks the searched location


🎨 Example User Flow
1️⃣ User clicks "Find My Location" → A marker appears at their actual location.
2️⃣ User clicks on any place on the map → A marker appears at that place.
3️⃣ A line is drawn between the two points 📍➡️📍
4️⃣ A popup appears on the destination marker showing the distance in meters.

