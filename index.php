<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lyrica</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800 font-sans">

<!-- [ユーザーの現在地] + [天気] + [気分] + [ジャンル] -->
<!-- ↓ -->
<!-- 文脈を構成（自然文にする） -->
<!-- ↓ -->
<!-- ChatGPTに投げて「Spotify検索用ワード」を生成 -->
<!-- ↓ -->
<!-- Spotify検索URLに投げて、1曲 or 複数曲に出会う -->
<!-- ↓ -->
<!-- ノーツを書いて保存 -->
<!-- ↓ -->
<!-- save_note.php に保存 → notes.csv -->
<!-- ↓ -->
<!-- load_note.phpで一覧表示 -->

<!-- 使用したAPI：OpenWeather + Geolocation API（※APIキー不要） -->

<!-- Richバージョンへの改良戦略 -->
<!-- ①ファイルをhtdocs/フォルダ内に移動 -->
<!-- ②index.html を index.php にリネーム -->
<!-- ③save_note.phpを新規に追加 -->
<!-- ④localStorageの使用をやめ（コメントアウト）、JSにfetch処理を追加→PHPに飛ばす。 -->

    <div class="max-w-md mx-auto p-6 bg-white shadow-md rounded-lg mt-6">
        <h1 class="text-3xl font-bold text-center mb-2">Lyrica</h1>
        <p class="text-center text-sm text-gray-600 mb-4">
        世界のすべてが、君のプレイリストになる
        </p>

    <!-- 気分入力 -->
    <label for="mood" class="block mb-1 font-medium">今の気分・場面：</label>
    <input
        type="text"
        id="mood"
        placeholder="例：星がきれい、恋をしている、ちょっとさびしい"
        class="w-full px-4 py-2 border rounded-md mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400"
    />

    <!-- ジャンル入力 -->
    <label for="genre" class="block mb-1 font-medium">ジャンル指定（任意）：</label>
    <input
        type="text"
        id="genre"
        placeholder="例：90年代J-Pop、Jazz、EDM、Lo-Fiなど"
        class="w-full px-4 py-2 border rounded-md mb-4"
    />

    <!-- 曲を探すボタン -->
    <button
        id="searchBtn"
        class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition"
    >
        🎵 曲を探す
    </button>

    <!-- 天気情報 -->
    <div class="mt-6">
        <h2 class="text-lg font-semibold">🌦 現在地と天気：</h2>
        <p id="weather" class="text-gray-700">現在地と天気を取得中...</p>
    </div>

    <!-- ChatGPTに渡すプロンプト -->
    <div id="promptSection" class="mt-6">
        <h2 class="text-lg font-semibold">🧠 ChatGPT用プロンプト</h2>
        <p
            id="generatedPrompt"
            class="text-sm text-gray-600 bg-gray-100 p-2 rounded whitespace-normal"
        >
            ここにおすすめ曲を検索するプロンプトが表示されます。<br />
            <br />
            コピペして生成AIに投げてください。
            <br />
            perplexity.com などのAI検索エンジンを使うと、より良い結果が得られます。
        </p>
    </div>


    <!-- おすすめ曲（Spotifyリンク） -->
    <div id="songResult" class="mt-6">
        <h2 class="text-lg font-semibold">🎧 おすすめの1曲：</h2>
        <a
            id="spotifyLink"
            href="https://open.spotify.com/intl-ja"
            target="_blank"
            rel="noopener noreferrer"
            class="text-green-600 underline block mt-1"
        >
            Spotifyで聴く
        </a>
    </div>


    <!-- ライナーノーツ保存 -->
    <section class="mt-6">
    <h2 class="text-xl font-semibold mb-2">🎼 ライナーノーツ</h2>

    <input
        type="text"
        id="songTitle"
        placeholder="曲名"
        class="w-full p-2 mb-2 border border-gray-300 rounded"
    />
    <input
        type="text"
        id="artistName"
        placeholder="アーティスト名"
        class="w-full p-2 mb-2 border border-gray-300 rounded"
    />
    <textarea
        id="linerNote"
        placeholder="この曲を聴いた気分、背景などを自由に書いてください"
        rows="4"
        class="w-full p-3 border border-gray-300 rounded resize-none"
    ></textarea>

    <div class="mt-3 flex items-center justify-between">
        <button
        id="saveNoteBtn"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
        >
        💾 保存する
        </button>
        <span id="saveMessage" class="text-green-600 text-sm hidden">保存しました ✅</span>
    </div>
    </section>

    <!-- ライナーノーツ一覧 -->
    <section class="mt-8">
        <h2 class="text-xl font-semibold mb-2">📚 あなたのノーツ一覧</h2>
        <ul id="noteList" class="space-y-3"></ul>
    </section>

</div>

<script src="./js/script.js"></script>
</body>
</html>
