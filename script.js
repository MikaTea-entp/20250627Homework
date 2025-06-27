// alert("gannbaruzo");

// 位置・天気・時刻の取得関数
async function getLocationWeatherAndTime() {
    return new Promise((resolve, reject) => {
        navigator.geolocation.getCurrentPosition(async (position) => {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            try {
                const apiKey = 'YOUR_API_KEY_HERE';
                const url = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric&lang=ja`;

                const res = await fetch(url);
                const data = await res.json();

                const city = data.name;
                const weather = data.weather[0].description;
                const temp = data.main.temp;

                const now = new Date();
                const formattedTime = now.toLocaleString('ja-JP', {
                    timeZone: 'Asia/Tokyo',
                    hour12: false
                });

                resolve({ city, weather, temp, time: formattedTime });
            } catch (err) {
                reject('天気情報の取得に失敗しました');
            }
        }, () => {
            reject('位置情報の取得に失敗しました');
        });
    });
}

// プロンプト生成関数
function generatePrompt(city, weatherDesc, moodText, genreText) {
    let base = `今、${city}は${weatherDesc}で、私は「${moodText}」と感じています。`;
    if (genreText && genreText.trim()) {
        base += `また、ジャンルとして「${genreText}」を希望します。`;
    }
    return base + `\nこの気分と状況に合う楽曲をいくつか提案してください。`;
}

document.getElementById('searchBtn').addEventListener('click', async () => {
    try {
        const { city, weather, temp, time } = await getLocationWeatherAndTime();
        document.getElementById('weather').textContent = `${city}・${weather}・${temp}℃（${time}）`;

        const mood = document.getElementById('mood').value;
        const genre = document.getElementById('genre').value;
        const prompt = generatePrompt(city, weather, mood, genre);
        document.getElementById('generatedPrompt').textContent = prompt;
    } catch (err) {
        document.getElementById('weather').textContent = err;
    }
});



// [曲を探す] ボタンのイベント
document.getElementById('searchBtn').addEventListener('click', async () => {
    try {
        const { city, weather, temp, time } = await getLocationWeatherAndTime();
        document.getElementById('weather').textContent = `${city}・${weather}・${temp}℃（${time}）`;

        const mood = document.getElementById('mood').value;
        const prompt = generatePrompt(city, weather, mood);
        document.getElementById('generatedPrompt').textContent = prompt;
    } catch (err) {
        document.getElementById('weather').textContent = err;
    }
});

// [保存する] ボタンのイベント
document.getElementById('saveNoteBtn').addEventListener('click', async () => {
    const title = document.getElementById('songTitle').value.trim();
    const artist = document.getElementById('artistName').value.trim();
    const note = document.getElementById('linerNote').value.trim();

    if (title && artist && note) {
        const formData = new FormData();
        formData.append('title', title);
        formData.append('artist', artist);
        formData.append('note', note);

        try {
            const res = await fetch('save_note.php', {
                method: 'POST',
                body: formData
            });

            const result = await res.json();
            if (result.status === "ok") {
                document.getElementById('saveMessage').classList.remove('hidden');
                setTimeout(() => {
                    document.getElementById('saveMessage').classList.add('hidden');
                }, 2000);

                document.getElementById('songTitle').value = '';
                document.getElementById('artistName').value = '';
                document.getElementById('linerNote').value = '';
                loadAllNotes();
            } else {
                alert("エラー: " + result.message);
            }
        } catch (err) {
            alert("通信エラーが発生しました");
        }
    }
});

// ノート一覧読み込み関数
async function loadAllNotes() {
    const noteList = document.getElementById('noteList');
    noteList.innerHTML = '';

    try {
        const res = await fetch('load_note.php');
        const notes = await res.json();

        notes.reverse().forEach(data => {
            const li = document.createElement('li');
            li.className = "border p-3 rounded shadow bg-white relative";
            li.innerHTML = `
                <p><strong>🎵 ${data.title}</strong> — ${data.artist}</p>
                <p class="text-gray-500 text-sm">${new Date(data.time).toLocaleString('ja-JP')}</p>
                <p class="mt-1 whitespace-pre-wrap">${data.note}</p>
            `;
            noteList.appendChild(li);
        });
    } catch (e) {
        noteList.innerHTML = '<p class="text-red-600">読み込みに失敗しました。</p>';
    }
}

// 初期化
window.addEventListener('DOMContentLoaded', () => {
    loadAllNotes();
});
