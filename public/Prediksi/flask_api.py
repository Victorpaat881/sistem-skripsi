from flask import Flask, request, jsonify
import tensorflow as tf
# import numpy as np
import pandas as pd
# from sklearn.preprocessing import StandardScaler
# import joblib
import re
import json
import pickle

app = Flask(__name__)

# Baca struktur kolom one-hot encoding
with open('one_hot_columns.json', 'r') as f:
    one_hot_columns = json.load(f)

# Muat scaler untuk input
with open('scaler_input.pkl', 'rb') as input_scaler_file:
    scaler_input = pickle.load(input_scaler_file)

# Muat scaler untuk output
with open('scaler_output.pkl', 'rb') as output_scaler_file:
    scaler_output = pickle.load(output_scaler_file)

# Buat test data dengan dinamis
def build_test_data(input_data, one_hot_columns):
    test_data = {col: 0 for col in one_hot_columns}

    for key, value in input_data.items():
        col_name = f"{key}_{value}"
        if col_name in one_hot_columns:
            test_data[col_name] = 1

    for non_one_hot_col in ['STOK AWAL', 'PENERIMAAN', 'PERSEDIAAN', 'PEMAKAIAN', 'SISA STOK', 'STOK OPTIMUM']:
        test_data[non_one_hot_col] = input_data[non_one_hot_col]

    return test_data

def preprocess(input_data):
    # Konversi data ke DataFrame
    input_df = pd.DataFrame([input_data])
    print(f"Processed data for prediction: {input_df}")

    # Preprocessing steps
    # 1. Pembersihan Data
    input_df['SATUAN'] = input_df['SATUAN'].str.replace('BTL', 'Botol', case=False)
    input_df['SATUAN'] = input_df['SATUAN'].str.replace('/', ' atau ')
    input_df['NAMA OBAT'] = input_df['NAMA OBAT'].str.replace('+', ' dan ')
    input_df['NAMA OBAT'] = input_df['NAMA OBAT'].str.replace('/', ' atau ')

    # Apply the function to change commas
    def ubah_koma(teks):
        if re.search(r'\d+,\d+', teks):
            teks = teks.replace(',', '.')
        else:
            teks = teks.replace(',', ' dan ')
        return teks

    input_df['NAMA OBAT'] = input_df['NAMA OBAT'].apply(ubah_koma)

    # 2. one-hot encoding
    input_df = build_test_data(input_data, one_hot_columns)

    # 3. Konversi data ke DataFrame lagi
    input_df = pd.DataFrame([input_df])

    # 4. Konversi Tipe Data (float32)
    input_df = input_df.astype('float32')

    # 5. Mengurutkan kolom
    # Definisikan urutan kolom yang diinginkan
    desired_order = ['STOK AWAL', 'PENERIMAAN', 'PERSEDIAAN', 'PEMAKAIAN', 'SISA STOK', 'STOK OPTIMUM']

    # Ambil kolom-kolom yang tidak termasuk dalam urutan yang diinginkan
    other_columns = [col for col in input_df.columns if col not in desired_order]

    # Gabungkan urutan yang diinginkan dengan kolom-kolom lainnya
    new_order = desired_order + other_columns

    # Reorder kolom-kolom dalam DataFrame
    input_df = input_df[new_order]
    
    # 6. Normalisasi
    input_df = scaler_input.transform(input_df)

    # 7. Reshaping
    input_df = input_df.reshape((1, 1, -1))

    return input_df

# Memuat modelnya
model = tf.keras.models.load_model('Prediksi_Kebutuhan_Obat.keras')
# for layer in model.layers:
#     print(layer.get_weights())

@app.route('/predict', methods=['POST'])
def predict():
    # Ambil JSON data
    input_data = request.get_json(force=True)

    # Preprocess data
    processed_data = preprocess(input_data)

    # Buat prediksi
    prediction = model.predict(processed_data)

    # Inverse transform
    prediction = scaler_output.inverse_transform(prediction)

    # Konversi nilai float menjadi int
    prediction = prediction.astype(int)

    # Mengembalikan prediksinya sebagai JSON
    return jsonify({'prediction': prediction.tolist()})

if __name__ == '__main__':
    app.run(debug=True)
